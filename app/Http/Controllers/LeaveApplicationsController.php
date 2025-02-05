<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveApplicationsRequest;
use App\Http\Requests\UpdateLeaveApplicationsRequest;
use App\Repositories\LeaveApplicationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\LeaveApplications;
use App\Models\LeaveSignatories;
use App\Models\LeaveAttendanceDates;
use App\Models\Users;
use App\Models\Notifications;
use App\Models\Employees;
use App\Models\LeaveDays;
use App\Models\AttendanceData;
use App\Models\IDGenerator;
use App\Models\PayrollSchedules;
use App\Models\LeaveBalances;
use App\Models\LeaveBalanceDetails;
use App\Models\LeaveImageAttachments;
use App\Models\HolidaysList;
use App\Models\SMSNotifications;
use App\Models\LeaveExcessAbsences;
use App\Models\Permission;
use DatePeriod;
use DateTime;
use DateInterval;
use Flash;
use Response;

class LeaveApplicationsController extends AppBaseController
{
    /** @var  LeaveApplicationsRepository */
    private $leaveApplicationsRepository;

    public function __construct(LeaveApplicationsRepository $leaveApplicationsRepo)
    {
        $this->middleware('auth');
        $this->leaveApplicationsRepository = $leaveApplicationsRepo;
    }

    /**
     * Display a listing of the LeaveApplications.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveApplications = $this->leaveApplicationsRepository->all();

        return view('leave_applications.index')
            ->with('leaveApplications', $leaveApplications);
    }

    /**
     * Show the form for creating a new LeaveApplications.
     *
     * @return Response
     */
    public function create()
    {
        $employee = Employees::find(Auth::user()->employee_id);
        if ($employee == null) {
            return abort('Employee not found!', 404);
        } else {
            $leaveBalance = LeaveBalances::where('EmployeeId', $employee->id)->first();
            
            return view('leave_applications.create', [
                'leaveBalance' => $leaveBalance,
                'employee' => $employee,
            ]);
        }        
    }

    /**
     * Store a newly created LeaveApplications in storage.
     *
     * @param CreateLeaveApplicationsRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveApplicationsRequest $request)
    {
        $input = $request->all();

        $leaveApplications = $this->leaveApplicationsRepository->create($input);

        Flash::success('Leave Applications saved successfully.');

        return redirect(route('leaveApplications.create-step-two', [$leaveApplications->id]));
    }

    /**
     * Display the specified LeaveApplications.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveApplications = $this->leaveApplicationsRepository->find($id);
        $leaveSignatories = DB::table('LeaveSignatories')
            ->leftJoin('users', 'LeaveSignatories.EmployeeId', '=', 'users.id')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->select(
                'LeaveSignatories.id', 
                'LeaveSignatories.EmployeeId', 
                'LeaveSignatories.Status', 
                'Employees.FirstName', 
                'Employees.LastName', 
                'Employees.MiddleName', 
                'Employees.Suffix', 
                'Positions.Position', 
                'LeaveSignatories.updated_at', 
                'LeaveSignatories.Notes')
            ->where('LeaveSignatories.LeaveId', $id)
            ->whereRaw("(LeaveSignatories.Status IS NULL OR LeaveSignatories.Status NOT IN('REMOVED'))")
            ->orderBy('LeaveSignatories.Rank')
            ->get();

        if (empty($leaveApplications)) {
            Flash::error('Leave Applications not found');

            return redirect(route('leaveApplications.index'));
        }

        $leaveDays = LeaveDays::where('LeaveId', $id)->orderBy('LeaveDate')->get();
        $leaveImgs = LeaveImageAttachments::where('LeaveId', $id)->get();

        return view('leave_applications.show', [
            'leaveApplication' => $leaveApplications,
            'leaveSignatories' => $leaveSignatories,
            'leaveDays' => $leaveDays,
            'leaveImgs' => $leaveImgs,
        ]);
    }

    /**
     * Show the form for editing the specified LeaveApplications.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveApplications = $this->leaveApplicationsRepository->find($id);

        if (empty($leaveApplications)) {
            Flash::error('Leave Applications not found');

            return redirect(route('leaveApplications.index'));
        }

        return view('leave_applications.edit')->with('leaveApplications', $leaveApplications);
    }

    /**
     * Update the specified LeaveApplications in storage.
     *
     * @param int $id
     * @param UpdateLeaveApplicationsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveApplicationsRequest $request)
    {
        $leaveApplications = $this->leaveApplicationsRepository->find($id);

        if (empty($leaveApplications)) {
            Flash::error('Leave Applications not found');

            return redirect(route('leaveApplications.index'));
        }

        $leaveApplications = $this->leaveApplicationsRepository->update($request->all(), $id);

        Flash::success('Leave Applications updated successfully.');

        return redirect(route('leaveApplications.index'));
    }

    /**
     * Remove the specified LeaveApplications from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveApplications = $this->leaveApplicationsRepository->find($id);

        if (empty($leaveApplications)) {
            Flash::error('Leave Applications not found');

            return redirect(route('leaveApplications.index'));
        }

        $this->leaveApplicationsRepository->delete($id);

        Flash::success('Leave Applications deleted successfully.');

        return redirect(route('users.leave-credits', [$leaveApplications->EmployeeId]));
    }

    public function createStepTwo($id) {
        $leaveApplication = LeaveApplications::find($id);

        LeaveSignatories::where('LeaveId', $id)
            ->delete();

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Positions.Department', 'Positions.ParentPositionId', 'Positions.Level')
            ->whereRaw("Employees.id='" . $leaveApplication->EmployeeId . "'")
            ->first();

        $users = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('users.id', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix')
            ->whereRaw("Positions.Department='" . $employee->Department . "' AND Positions.Level IN ('Manager', 'Chief', 'Supervisor')")
            ->get();

        if (in_array($employee->Level, ['Chief', 'Manager'])) {
            $signatories = Employees::getSupers($leaveApplication->EmployeeId, ['Chief', 'Manager', 'General Manager']);
        } else {
            $signatories = Employees::getSupers($leaveApplication->EmployeeId, ['Chief', 'Manager']);
        }

        $rank = 1;
        if ($signatories != null) {
            foreach($signatories as $signatory) {
                $sigs = new LeaveSignatories;
                // $sigs->id = IDGenerator::generateIDandRandString() . $rank;
                $sigs->LeaveId = $id;
                $sigs->EmployeeId = $signatory['id'];
                $sigs->Rank = $rank;
                $sigs->save();
    
                $rank++;
            }
        }

        $leaveSignatories = DB::table('LeaveSignatories')
            ->leftJoin('users', 'LeaveSignatories.EmployeeId', '=', DB::raw("TRY_CAST(users.id AS VARCHAR)"))
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('LeaveSignatories.id', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Position')
            ->where('LeaveSignatories.LeaveId', $id)
            ->whereRaw("(LeaveSignatories.Status IS NULL OR LeaveSignatories.Status NOT IN('REMOVED'))")
            ->get();

        $leaveDays = LeaveDays::where('LeaveId', $id)->orderBy('LeaveDate')->get();

        $leaveBalance = DB::table('LeaveBalances')
            ->select(DB::raw($leaveApplication->LeaveType . ' AS Balance'))
            ->where('EmployeeId', $leaveApplication->EmployeeId)
            ->first();

        $holidaysList = HolidaysList::whereRaw("HolidayDate > GETDATE()")->get();
        $holidays = "";
        $size = count($holidaysList);
        $i = 0;
        foreach($holidaysList as $item) {
            if ($i == ($size - 1)) {
                $holidays .= date('Y-m-d', strtotime($item->HolidayDate));
            } else {
                $holidays .= date('Y-m-d', strtotime($item->HolidayDate)) . ',';
            }
            $i++;
        }
        // $holidays = "[" . $holidays . "]";

        $leaveImgs = LeaveImageAttachments::where('LeaveId', $id)->get();

        return view('/leave_applications/create_step_two', [
            'leaveApplication' => $leaveApplication,
            'users' => $users,
            'leaveSignatories' => $leaveSignatories,
            'leaveDays' => $leaveDays,
            'leaveBalance' => $leaveBalance,
            'leaveImgs' => $leaveImgs,
            'holidays' => $holidays,
        ]);
    }

    public function addSignatories(Request $request) {
        if ($request->ajax()) {
            $leaveSignatories = LeaveSignatories::where('EmployeeId', $request['UserId'])->where('LeaveId', $request['LeaveId'])->first();
            $existingSignatories = LeaveSignatories::where('LeaveId', $request['LeaveId'])->get();

            if ($leaveSignatories == null) {
                $leaveSignatories = new LeaveSignatories;
                $leaveSignatories->LeaveId = $request['LeaveId'];
                $leaveSignatories->EmployeeId = $request['UserId'];
                $leaveSignatories->Rank = count($existingSignatories)+1;
                $leaveSignatories->save();

                if ((count($existingSignatories)+1) == 1) {
                    $notifications = new Notifications;
                    $notifications->UserId = $request['UserId'];
                    $notifications->Type = 'LEAVE_APPROVAL';
                    $notifications->Content = Auth::user()->name . " has filed a leave. Check it out to approve.";
                    $notifications->Notes = $request['LeaveId'];
                    $notifications->Status = "UNREAD";
                    $notifications->save();
                }

                return response()->json($leaveSignatories, 200);
            } else {
                return response()->json(['response' => 'exists'], 200);
            }
        }
    }

    public function leaveApprovals($id) {
        $leaveApplication = LeaveApplications::find($id);
        $leaveSignatories = DB::table('LeaveSignatories')
            ->leftJoin('users', 'LeaveSignatories.EmployeeId', '=', 'users.id')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->select('LeaveSignatories.id', 'LeaveSignatories.EmployeeId', 'LeaveSignatories.Status', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Employees.Designation')
            ->where('LeaveSignatories.LeaveId', $id)
            ->whereRaw("(LeaveSignatories.Status IS NULL OR LeaveSignatories.Status NOT IN('REMOVED'))")
            ->get();

        return view('/leave_applications/approvals',[
            'leaveApplication' => $leaveApplication,
            'leaveSignatories' => $leaveSignatories,
        ]);
    }

    public function approveLeave($id, $signatoryId) {        
        $leaveApplication = LeaveApplications::find($id);
        $leaveSignatory = LeaveSignatories::find($signatoryId);

        // UPDATE SIGNATORIES
        $leaveSignatory->Status = 'APPROVED';
        $leaveSignatory->save();

        // GET USER
        $user = Users::where('employee_id', $leaveApplication->EmployeeId)->first();
        $employee = Employees::find($leaveApplication->EmployeeId);
        $payrollSchedule = PayrollSchedules::find($employee->PayrollScheduleId);

        // ADD NOTIFICATION FOR THE REQUISITIONER
        $notifications = new Notifications;
        $notifications->UserId = $user != null ? $user->id : '';
        $notifications->Type = 'LEAVE_INFO';
        $notifications->Content = Users::find($leaveSignatory->EmployeeId)->name . ' has approved your leave.';
        $notifications->Notes = $id;
        $notifications->Status = "UNREAD";
        $notifications->save();

        // ADD NOTIFICATIONS FOR NEXT SIGNATORY
        $nextSignatory = DB::table('LeaveSignatories')
            ->whereRaw("LeaveId='" . $id . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $leaveSignatory->Rank)
            ->orderBy('Rank')
            ->first();

        if ($nextSignatory !== null) {
            // IF LEAVE IS STILL NOTE COMPLETED SIGNING
            $notifications = new Notifications;
            $notifications->UserId = $nextSignatory->EmployeeId;
            $notifications->Type = 'LEAVE_APPROVAL';
            $notifications->Content = Employees::getMergeName(Employees::find($leaveApplication->EmployeeId)) . " has filed a leave. Check it out to approve.";
            $notifications->Notes = $id;
            $notifications->Status = "UNREAD";
            $notifications->save();

            // UPDATE LEAVE STATUS
            $leaveApplication->Status = 'Partially Approved';
            $leaveApplication->save();

            /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HR System - Leave Approval:\n\n" . Users::find($leaveSignatory->EmployeeId)->name . " has approved your " . $leaveApplication->LeaveType . " leave. 
                        Your leave is now forwarded to the next signatory.",
                    "HR-Leave",
                    $id
                );
            }
        } else {
            // UPDATE LEAVE STATUS
            // THIS PORTION IS WHEN THE LEAVE HAS FULLY SIGNED BY ALL SIGNATORIES
            /**
             * FILTER IF SICK LEAVE, NEEDS TO BE APPROVED BY HR
            */
            if ($leaveApplication->LeaveType === 'Sick') {
                $leaveApplication->Status = 'FOR REVIEW';
                $leaveApplication->save();
            } else {
                $leaveApplication->Status = 'APPROVED';
                $leaveApplication->save();

                // PLOT LEAVE DAYS TO ATTENDANCE DATA
                $leaveDays = LeaveDays::where('LeaveId', $id)->get();
                $totalDays = 0.0;
                foreach($leaveDays as $item) {
                    if ($item->Duration == 'WHOLE') {
                        // INSERT START MORNING IN
                        $attendance = new AttendanceData;
                        $attendance->id = IDGenerator::generateIDandRandString();
                        $attendance->BiometricUserId = $employee->BiometricsUserId;
                        $attendance->EmployeeId = $employee->id;
                        $attendance->UserId = $user->id;
                        $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 07:31:00';
                        $attendance->AbsentPermission = 'LEAVE';
                        $attendance->save();

                        // INSERT START MORNING OUT
                        $attendance = new AttendanceData;
                        $attendance->id = IDGenerator::generateIDandRandString();
                        $attendance->BiometricUserId = $employee->BiometricsUserId;
                        $attendance->EmployeeId = $employee->id;
                        $attendance->UserId = $user->id;
                        $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 12:05:00';
                        $attendance->AbsentPermission = 'LEAVE';
                        $attendance->save();

                        // INSERT START AFTERNOON IN
                        $attendance = new AttendanceData;
                        $attendance->id = IDGenerator::generateIDandRandString();
                        $attendance->BiometricUserId = $employee->BiometricsUserId;
                        $attendance->EmployeeId = $employee->id;
                        $attendance->UserId = $user->id;
                        $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 12:45:00';
                        $attendance->AbsentPermission = 'LEAVE';
                        $attendance->save();

                        // INSERT START AFTERNOON OUT
                        $attendance = new AttendanceData;
                        $attendance->id = IDGenerator::generateIDandRandString();
                        $attendance->BiometricUserId = $employee->BiometricsUserId;
                        $attendance->EmployeeId = $employee->id;
                        $attendance->UserId = $user->id;
                        $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 17:05:00';
                        $attendance->AbsentPermission = 'LEAVE';
                        $attendance->save();

                        $totalDays += 1;
                    } elseif ($item->Duration == 'AM') {
                        // INSERT START MORNING IN
                        $attendance = new AttendanceData;
                        $attendance->id = IDGenerator::generateIDandRandString();
                        $attendance->BiometricUserId = $employee->BiometricsUserId;
                        $attendance->EmployeeId = $employee->id;
                        $attendance->UserId = $user->id;
                        $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 07:31:00';
                        $attendance->AbsentPermission = 'LEAVE';
                        $attendance->save();

                        // INSERT START MORNING OUT
                        $attendance = new AttendanceData;
                        $attendance->id = IDGenerator::generateIDandRandString();
                        $attendance->BiometricUserId = $employee->BiometricsUserId;
                        $attendance->EmployeeId = $employee->id;
                        $attendance->UserId = $user->id;
                        $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 12:05:00';
                        $attendance->AbsentPermission = 'LEAVE';
                        $attendance->save();

                        $totalDays += .5;
                    } elseif ($item->Duration == 'PM') {
                        // INSERT START AFTERNOON IN
                        $attendance = new AttendanceData;
                        $attendance->id = IDGenerator::generateIDandRandString();
                        $attendance->BiometricUserId = $employee->BiometricsUserId;
                        $attendance->EmployeeId = $employee->id;
                        $attendance->UserId = $user->id;
                        $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 12:45:00';
                        $attendance->AbsentPermission = 'LEAVE';
                        $attendance->save();

                        // INSERT START AFTERNOON OUT
                        $attendance = new AttendanceData;
                        $attendance->id = IDGenerator::generateIDandRandString();
                        $attendance->BiometricUserId = $employee->BiometricsUserId;
                        $attendance->EmployeeId = $employee->id;
                        $attendance->UserId = $user->id;
                        $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 17:05:00';
                        $attendance->AbsentPermission = 'LEAVE';
                        $attendance->save();

                        $totalDays += .5;
                    }
                }

                // UPDATE LEAVE BALANCES
                $leaveBalances = LeaveBalances::where('EmployeeId', $leaveApplication->EmployeeId)->first();
                if ($leaveBalances != null) {
                    if ($leaveApplication->LeaveType == 'Sick') {                        
                        $balance = floatval($leaveBalances->Sick);
                        $days = $totalDays;

                        if ($balance < $days) {
                            $balance = 0;
                        } else {
                            $balance = $balance - $days;
                        }                        

                        $leaveBalances->Sick = $balance;

                    } elseif ($leaveApplication->LeaveType == 'Vacation') {
                        $balance = floatval($leaveBalances->Vacation);
                        $days = count($leaveDays);
                        
                        if ($balance < $days) {
                            $balance = 0;
                        } else {
                            $balance = $balance - $days;
                        } 

                        $leaveBalances->Vacation = $balance;
                    } elseif ($leaveApplication->LeaveType == 'Special') {
                        $balance = floatval($leaveBalances->Special);
                        $days = count($leaveDays);
                        
                        if ($balance < $days) {
                            $balance = 0;
                        } else {
                            $balance = $balance - $days;
                        } 

                        $leaveBalances->Special = $balance;
                    } elseif ($leaveApplication->LeaveType == 'Paternity') {
                        $balance = floatval($leaveBalances->Paternity);
                        $days = count($leaveDays);
                        
                        if ($balance < $days) {
                            $balance = 0;
                        } else {
                            $balance = $balance - $days;
                        } 

                        $leaveBalances->Paternity = $balance;
                    } elseif ($leaveApplication->LeaveType == 'Maternity') {
                        $balance = floatval($leaveBalances->Maternity);
                        $days = count($leaveDays);
                        
                        if ($balance < $days) {
                            $balance = 0;
                        } else {
                            $balance = $balance - $days;
                        } 

                        $leaveBalances->Maternity = $balance;
                    } elseif ($leaveApplication->LeaveType == 'MaternityForSoloMother') {
                        $balance = floatval($leaveBalances->MaternityForSoloMother);
                        $days = count($leaveDays);
                        
                        if ($balance < $days) {
                            $balance = 0;
                        } else {
                            $balance = $balance - $days;
                        } 

                        $leaveBalances->MaternityForSoloMother = $balance;
                    } elseif ($leaveApplication->LeaveType == 'SoloParent') {
                        $balance = floatval($leaveBalances->SoloParent);
                        $days = count($leaveDays);
                        
                        if ($balance < $days) {
                            $balance = 0;
                        } else {
                            $balance = $balance - $days;
                        } 

                        $leaveBalances->SoloParent = $balance;
                    }
                    $leaveBalances->save();
                }
            }  
            
             /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HR System - Leave Approval:\n\n" . Users::find($leaveSignatory->EmployeeId)->name . " has approved your " . $leaveApplication->LeaveType . " leave.",
                    "HR-Leave",
                    $id
                );
            }
        }
        
        return redirect(route('home'));
    }

    public function myApprovals(Request $request) {
        $leaves = DB::table('LeaveSignatories')
            ->leftJoin('LeaveApplications', 'LeaveSignatories.LeaveId', '=', 'LeaveApplications.id')
            ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
            ->whereRaw("LeaveSignatories.EmployeeId='" . Auth::id() . "' AND LeaveSignatories.Status IS NULL AND LeaveSignatories.id IN 
                (SELECT TOP 1 x.id FROM LeaveSignatories x WHERE x.LeaveId=LeaveSignatories.LeaveId AND x.Status IS NULL ORDER BY x.Rank)")
            ->select('LeaveApplications.*',
                'LeaveSignatories.id AS SignatoryId',
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',)
            ->get();

        return view('/leave_applications/my_approvals', [
            'leaves' => $leaves
        ]);
    }

    public function approveAjax(Request $request) {
        $id = $request['id'];
        $signatoryId = $request['SignatoryId'];

        $leaveApplication = LeaveApplications::find($id);
        $leaveSignatory = LeaveSignatories::find($signatoryId);

        // UPDATE SIGNATORIES
        $leaveSignatory->Status = 'APPROVED';
        $leaveSignatory->save();

        // GET USER
        $user = Users::where('employee_id', $leaveApplication->EmployeeId)->first();
        $employee = Employees::find($leaveApplication->EmployeeId);
        $payrollSchedule = PayrollSchedules::find($employee->PayrollScheduleId);

        // ADD NOTIFICATION FOR THE REQUISITIONER
        $notifications = new Notifications;
        $notifications->UserId = $user != null ? $user->id : '';
        $notifications->Type = 'LEAVE_INFO';
        $notifications->Content = Users::find($leaveSignatory->EmployeeId)->name . ' has approved your leave.';
        $notifications->Notes = $id;
        $notifications->Status = "UNREAD";
        $notifications->save();

        // ADD NOTIFICATIONS FOR NEXT SIGNATORY
        // ADD NOTIFICATIONS FOR NEXT SIGNATORY
        $nextSignatory = DB::table('LeaveSignatories')
            ->whereRaw("LeaveId='" . $id . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $leaveSignatory->Rank)
            ->orderBy('Rank')
            ->first();

        if ($nextSignatory != null) {
            // IF LEAVE IS STILL NOTE COMPLETED SIGNING
            $notifications = new Notifications;
            $notifications->UserId = $nextSignatory->EmployeeId;
            $notifications->Type = 'LEAVE_APPROVAL';
            $notifications->Content = Employees::getMergeName(Employees::find($leaveApplication->EmployeeId)) . " has filed a leave. Check it out to approve.";
            $notifications->Notes = $id;
            $notifications->Status = "UNREAD";
            $notifications->save();

            /**
             * =========================================================================
             * SEND SMS TO NEXT SIGNATORY
             * =========================================================================
             */
            $u = Users::find($nextSignatory->EmployeeId);
            if ($u != null) {
                $fRank = Employees::find($u->employee_id);

                if ($fRank != null && $fRank->ContactNumbers != null) {
                    SMSNotifications::sendSMS($fRank->ContactNumbers, 
                        "HRS Leave Approval\n\nHello " . $fRank->FirstName . ", " . $employee->FirstName . " " . $employee->LastName . " has filed a leave that needs your approval. " .
                            "Kindly check your HR System approval module for more info.",
                        "HR-Leave",
                        $id
                    );
                }
            }

            /**
             * =========================================================================
             * SEND SMS TO EMPLOYEE
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS Leave Approval\n\nHello " . $employee->FirstName . ", " . Users::find($leaveSignatory->EmployeeId)->name . " has APPROVED your " . $leaveApplication->LeaveType . " leave application. It is now forwarded to the next signatory.",
                    "HR-Leave",
                    $id
                );
            }

            // UPDATE LEAVE STATUS
            $leaveApplication->Status = 'Partially Approved';
            $leaveApplication->save();
            
        } else {
            // UPDATE LEAVE STATUS
            // THIS PORTION IS WHEN THE LEAVE HAS FULLY SIGNED BY ALL SIGNATORIES
            /**
             * FILTER IF SICK LEAVE, NEEDS TO BE APPROVED BY HR
            */
            // if ($leaveApplication->LeaveType == 'Sick') {
            //     $leaveApplication->Status = 'FOR REVIEW';
            //     $leaveApplication->save();
            // } else {
                
            // }     

            /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS Leave Approval\n\nHello " . $employee->FirstName . ", " . Users::find($leaveSignatory->EmployeeId)->name . " has APPROVED your " . $leaveApplication->LeaveType . " leave.",
                    "HR-Leave",
                    $id
                );
            }

            // PLOT LEAVE DAYS TO ATTENDANCE DATA
            $leaveDays = LeaveDays::where('LeaveId', $id)->get();
            $totalDays = 0.0;
            $totalCredits = 0;
            foreach($leaveDays as $item) {
                $totalMins = 0;
                $leaveDays = 0;

                if ($item->Duration == 'WHOLE') {
                    // increment total minutes for vacation and sick leave
                    if ($leaveApplication->LeaveType === 'Vacation' | $leaveApplication->LeaveType === 'Sick') {
                        $totalMins = (8 * 60);
                    } else {
                        $leaveDays = 1;
                    }

                    // INSERT START MORNING IN
                    $attendance = new AttendanceData;
                    $attendance->id = IDGenerator::generateIDandRandString();
                    $attendance->BiometricUserId = $employee->BiometricsUserId;
                    $attendance->EmployeeId = $employee->id;
                    $attendance->UserId = $user->id;
                    $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 07:31:00';
                    $attendance->AbsentPermission = 'LEAVE';
                    $attendance->save();

                    // INSERT START MORNING OUT
                    $attendance = new AttendanceData;
                    $attendance->id = IDGenerator::generateIDandRandString();
                    $attendance->BiometricUserId = $employee->BiometricsUserId;
                    $attendance->EmployeeId = $employee->id;
                    $attendance->UserId = $user->id;
                    $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 12:05:00';
                    $attendance->AbsentPermission = 'LEAVE';
                    $attendance->save();

                    // INSERT START AFTERNOON IN
                    $attendance = new AttendanceData;
                    $attendance->id = IDGenerator::generateIDandRandString();
                    $attendance->BiometricUserId = $employee->BiometricsUserId;
                    $attendance->EmployeeId = $employee->id;
                    $attendance->UserId = $user->id;
                    $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 12:45:00';
                    $attendance->AbsentPermission = 'LEAVE';
                    $attendance->save();

                    // INSERT START AFTERNOON OUT
                    $attendance = new AttendanceData;
                    $attendance->id = IDGenerator::generateIDandRandString();
                    $attendance->BiometricUserId = $employee->BiometricsUserId;
                    $attendance->EmployeeId = $employee->id;
                    $attendance->UserId = $user->id;
                    $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 17:05:00';
                    $attendance->AbsentPermission = 'LEAVE';
                    $attendance->save();

                    $totalDays += 1;
                } elseif ($item->Duration == 'AM') {
                    // increment total minutes for vacation and sick leave
                    if ($leaveApplication->LeaveType === 'Vacation' | $leaveApplication->LeaveType === 'Sick') {
                        $totalMins = (4 * 60);
                    } else {
                        $leaveDays = .5;
                    }

                    // INSERT START MORNING IN
                    $attendance = new AttendanceData;
                    $attendance->id = IDGenerator::generateIDandRandString();
                    $attendance->BiometricUserId = $employee->BiometricsUserId;
                    $attendance->EmployeeId = $employee->id;
                    $attendance->UserId = $user->id;
                    $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 07:31:00';
                    $attendance->AbsentPermission = 'LEAVE';
                    $attendance->save();

                    // INSERT START MORNING OUT
                    $attendance = new AttendanceData;
                    $attendance->id = IDGenerator::generateIDandRandString();
                    $attendance->BiometricUserId = $employee->BiometricsUserId;
                    $attendance->EmployeeId = $employee->id;
                    $attendance->UserId = $user->id;
                    $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 12:05:00';
                    $attendance->AbsentPermission = 'LEAVE';
                    $attendance->save();

                    $totalDays += .5;
                } elseif ($item->Duration == 'PM') {
                    // increment total minutes for vacation and sick leave
                    if ($leaveApplication->LeaveType === 'Vacation' | $leaveApplication->LeaveType === 'Sick') {
                        $totalMins = (4 * 60);
                    } else {
                        $leaveDays = .5;
                    }

                    // INSERT START AFTERNOON IN
                    $attendance = new AttendanceData;
                    $attendance->id = IDGenerator::generateIDandRandString();
                    $attendance->BiometricUserId = $employee->BiometricsUserId;
                    $attendance->EmployeeId = $employee->id;
                    $attendance->UserId = $user->id;
                    $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 12:45:00';
                    $attendance->AbsentPermission = 'LEAVE';
                    $attendance->save();

                    // INSERT START AFTERNOON OUT
                    $attendance = new AttendanceData;
                    $attendance->id = IDGenerator::generateIDandRandString();
                    $attendance->BiometricUserId = $employee->BiometricsUserId;
                    $attendance->EmployeeId = $employee->id;
                    $attendance->UserId = $user->id;
                    $attendance->Timestamp = date('Y-m-d', strtotime($item->LeaveDate)) . ' 17:05:00';
                    $attendance->AbsentPermission = 'LEAVE';
                    $attendance->save();

                    $totalDays += .5;
                }

                // update balance
                $leaveBalances = LeaveBalances::where('EmployeeId', $leaveApplication->EmployeeId)->first();
                if ($leaveBalances != null) {
                    if ($leaveApplication->LeaveType == 'Sick') {                        
                        $balance = floatval($leaveBalances->Sick);
                        $mins = $totalMins;

                        if ($balance < $mins) {
                            // save sobra nga leave as absent inside LeaveExcessAbsences
                            $excessInMins = ($mins - $balance);
                            $lea = new LeaveExcessAbsences;
                            $lea->id = IDGenerator::generateIDandRandString();
                            $lea->EmployeeId = $leaveApplication->EmployeeId;
                            $lea->HoursAbsent = $excessInMins;
                            $lea->LeaveDate = $item->LeaveDate;
                            $lea->Notes = 'Excess leave application (Sick leave)';
                            $lea->save();

                            $balance = 0;
                            $totalCredits += $balance;
                        } else {
                            $balance = $balance - $mins;
                            $totalCredits += $mins;
                        }                        

                        $leaveBalances->Sick = $balance;

                    } elseif ($leaveApplication->LeaveType == 'Vacation') {
                        $balance = floatval($leaveBalances->Vacation);
                        $mins = $totalMins;
                        
                        if ($balance < $mins) {
                            // save sobra nga leave as absent inside LeaveExcessAbsences
                            $excessInMins = ($mins - $balance);
                            $lea = new LeaveExcessAbsences;
                            $lea->id = IDGenerator::generateIDandRandString();
                            $lea->EmployeeId = $leaveApplication->EmployeeId;
                            $lea->HoursAbsent = $excessInMins;
                            $lea->LeaveDate = $item->LeaveDate;
                            $lea->Notes = 'Excess leave application (Vacation leave)';
                            $lea->save();

                            $balance = 0;
                            $totalCredits += $balance;
                        } else {
                            $balance = $balance - $mins;
                            $totalCredits += $mins;
                        } 

                        $leaveBalances->Vacation = $balance;
                    } elseif ($leaveApplication->LeaveType == 'Special') {
                        $balance = floatval($leaveBalances->Special);
                        $daysL = $leaveDays;
                        
                        if ($balance < $daysL) {
                            $balance = 0;

                            // save sobra nga leave as absent inside LeaveExcessAbsences
                            $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                            $lea = new LeaveExcessAbsences;
                            $lea->id = IDGenerator::generateIDandRandString();
                            $lea->EmployeeId = $leaveApplication->EmployeeId;
                            $lea->HoursAbsent = $excessInMins;
                            $lea->LeaveDate = $item->LeaveDate;
                            $lea->Notes = 'Excess leave application (Special leave)';
                            $lea->save();

                            $totalCredits += $balance;
                        } else {
                            $balance = $balance - $daysL;
                            $totalCredits += $daysL;
                        } 

                        $leaveBalances->Special = $balance;
                    } elseif ($leaveApplication->LeaveType == 'Paternity') {
                        $balance = floatval($leaveBalances->Paternity);
                        $daysL = $leaveDays;
                        
                        if ($balance < $daysL) {
                            $balance = 0;

                            // save sobra nga leave as absent inside LeaveExcessAbsences
                            $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                            $lea = new LeaveExcessAbsences;
                            $lea->id = IDGenerator::generateIDandRandString();
                            $lea->EmployeeId = $leaveApplication->EmployeeId;
                            $lea->HoursAbsent = $excessInMins;
                            $lea->LeaveDate = $item->LeaveDate;
                            $lea->Notes = 'Excess leave application (Paternity leave)';
                            $lea->save();
                            
                            $totalCredits += $balance;
                        } else {
                            $balance = $balance - $daysL;
                            $totalCredits += $daysL;
                        } 

                        $leaveBalances->Paternity = $balance;
                    } elseif ($leaveApplication->LeaveType == 'Maternity') {
                        $balance = floatval($leaveBalances->Maternity);
                        $daysL = $leaveDays;
                        
                        if ($balance < $daysL) {
                            $balance = 0;

                            // save sobra nga leave as absent inside LeaveExcessAbsences
                            $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                            $lea = new LeaveExcessAbsences;
                            $lea->id = IDGenerator::generateIDandRandString();
                            $lea->EmployeeId = $leaveApplication->EmployeeId;
                            $lea->HoursAbsent = $excessInMins;
                            $lea->LeaveDate = $item->LeaveDate;
                            $lea->Notes = 'Excess leave application (Maternity leave)';
                            $lea->save();
                            
                            $totalCredits += $balance;
                        } else {
                            $balance = $balance - $daysL;
                            $totalCredits += $daysL;
                        } 

                        $leaveBalances->Maternity = $balance;
                    } elseif ($leaveApplication->LeaveType == 'MaternityForSoloMother') {
                        $balance = floatval($leaveBalances->MaternityForSoloMother);
                        $daysL = $leaveDays;
                        
                        if ($balance < $daysL) {
                            $balance = 0;

                            // save sobra nga leave as absent inside LeaveExcessAbsences
                            $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                            $lea = new LeaveExcessAbsences;
                            $lea->id = IDGenerator::generateIDandRandString();
                            $lea->EmployeeId = $leaveApplication->EmployeeId;
                            $lea->HoursAbsent = $excessInMins;
                            $lea->LeaveDate = $item->LeaveDate;
                            $lea->Notes = 'Excess leave application (Maternity For Solo Mother leave)';
                            $lea->save();
                            
                            $totalCredits += $balance;
                        } else {
                            $balance = $balance - $daysL;
                            $totalCredits += $daysL;
                        } 

                        $leaveBalances->MaternityForSoloMother = $balance;
                    } elseif ($leaveApplication->LeaveType == 'SoloParent') {
                        $balance = floatval($leaveBalances->SoloParent);
                        $daysL = $leaveDays;
                        
                        if ($balance < $daysL) {
                            $balance = 0;
                            
                            // save sobra nga leave as absent inside LeaveExcessAbsences
                            $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                            $lea = new LeaveExcessAbsences;
                            $lea->id = IDGenerator::generateIDandRandString();
                            $lea->EmployeeId = $leaveApplication->EmployeeId;
                            $lea->HoursAbsent = $excessInMins;
                            $lea->LeaveDate = $item->LeaveDate;
                            $lea->Notes = 'Excess leave application (Solo Parent leave)';
                            $lea->save();
                            
                            $totalCredits += $balance;
                        } else {
                            $balance = $balance - $daysL;
                            $totalCredits += $daysL;
                        } 

                        $leaveBalances->SoloParent = $balance;
                    }

                    $leaveBalances->save();
                }
            }  
            
            $leaveApplication->TotalCredits = $totalCredits;
            $leaveApplication->Status = 'APPROVED';
            $leaveApplication->save();

            // UPDATE LEAVE DAYS STATUS
            LeaveDays::where('LeaveId', $id)
                ->update(['Status' => 'APPROVED']);               
        }
        
        return response()->json('ok', 200);
    }

    public function deleteLeave(Request $request) {
        $id = $request['id'];

        if (Permission::hasDirectPermission(['god permission', 'delete leave'])) {
            $leaveApplication = LeaveApplications::find($id);

            // DELETE BIOMETRIC DATA IN AttendanceData table
            $leaveDays = LeaveDays::where('LeaveId', $id)->get();
            foreach($leaveDays as $item) {
                AttendanceData::where('AbsentPermission', 'LEAVE')
                    ->whereRaw("TRY_CAST(Timestamp AS DATE)='" . $item->LeaveDate . "' AND EmployeeId='" . $leaveApplication->EmployeeId . "'")
                    ->delete();
            }

            // re-add balance
            $leaveDays = LeaveDays::where('LeaveId', $id)->get();
            $totalBalDays = 0;
            foreach($leaveDays as $item) {
                $totalBalDays += floatval($item->Longevity);
            }

            $balanceToAdd = $leaveApplication->TotalCredits != null ? $leaveApplication->TotalCredits : 0;

            $balances = LeaveBalances::where('EmployeeId', $leaveApplication->EmployeeId)->orderByDesc('created_at')->first();
            if ($balances != null && $leaveApplication->Status === 'APPROVED') {
                if ($leaveApplication->LeaveType === 'Vacation') {
                    $existingBal = $balances->Vacation != null ? floatval($balances->Vacation) : 0;
                    $existingBal = $existingBal + $balanceToAdd;
                    $balances->Vacation = $existingBal;
                    $balances->save();
                } elseif ($leaveApplication->LeaveType === 'Sick') {
                    $existingBal = $balances->Sick != null ? floatval($balances->Sick) : 0;
                    $existingBal = $existingBal + $balanceToAdd;
                    $balances->Sick = $existingBal;
                    $balances->save();
                } elseif ($leaveApplication->LeaveType === 'Special') {
                    $existingBal = $balances->Special != null ? floatval($balances->Special) : 0;
                    $existingBal = $existingBal + $balanceToAdd;
                    $balances->Special = $existingBal;
                    $balances->save();
                } elseif ($leaveApplication->LeaveType === 'Paternity') {
                    $existingBal = $balances->Paternity != null ? floatval($balances->Paternity) : 0;
                    $existingBal = $existingBal + $balanceToAdd;
                    $balances->Paternity = $existingBal;
                    $balances->save();
                } elseif ($leaveApplication->LeaveType === 'Maternity') {
                    $existingBal = $balances->Maternity != null ? floatval($balances->Maternity) : 0;
                    $existingBal = $existingBal + $balanceToAdd;
                    $balances->Maternity = $existingBal;
                    $balances->save();
                } elseif ($leaveApplication->LeaveType === 'MaternityForSoloMother') {
                    $existingBal = $balances->MaternityForSoloMother != null ? floatval($balances->MaternityForSoloMother) : 0;
                    $existingBal = $existingBal + $balanceToAdd;
                    $balances->MaternityForSoloMother = $existingBal;
                    $balances->save();
                } elseif ($leaveApplication->LeaveType === 'SoloParent') {
                    $existingBal = $balances->SoloParent != null ? floatval($balances->SoloParent) : 0;
                    $existingBal = $existingBal + $balanceToAdd;
                    $balances->SoloParent = $existingBal;
                    $balances->save();
                }
            }

            $leaveApplication->delete();

            LeaveDays::where('LeaveId', $id)->delete();

            LeaveImageAttachments::where('LeaveId', $id)->delete();

            LeaveSignatories::where('LeaveId', $id)->delete();

            return response()->json('ok', 200);
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    public function addImageAttachments(Request $request) {
        $leaveId = $request['LeaveId'];
        $hexImage = urldecode($request['HexImage']);

        $img = new LeaveImageAttachments;
        $img->id = IDGenerator::generateIDandRandString();
        $img->LeaveId = $leaveId;
        $img->HexImage = $hexImage;
        $img->save();

        return response()->json($img, 200);
    }

    public function removeImage(Request $request) {
        $id = $request['id'];

        LeaveImageAttachments::find($id)->delete();

        return response()->json('ok', 200);
    }

    public function removeLeaveSignatory(Request $request) {
        $id = $request['id'];

        $leaveSignatory = LeaveSignatories::find($id);

        if ($leaveSignatory != null) {
            $leaveSignatory->Status = 'REMOVED';
            $leaveSignatory->save();
        }

        return response()->json($leaveSignatory, 200);
    }

    public function rejectLeaveAjax(Request $request) {
        $id = $request['id'];
        $signatoryId = $request['SignatoryId'];
        $notes = $request['Notes'];

        $leaveApplication = LeaveApplications::find($id);
        $leaveSignatory = LeaveSignatories::find($signatoryId);
        $employee = Employees::find($leaveApplication->EmployeeId);

        if ($leaveApplication != null) {
            $leaveApplication->Status = 'REJECTED';
            $leaveApplication->save();
        }

        if ($leaveSignatory != null) {
            $leaveSignatory->Status = 'REJECTED';
            $leaveSignatory->Notes = $notes;
            $leaveSignatory->save();

            $nextSignatories = DB::table('LeaveSignatories')
                ->whereRaw("LeaveId='" . $id . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $leaveSignatory->Rank)
                ->orderBy('Rank')
                ->get();
            foreach ($nextSignatories as $item) {
                $leaveSig = LeaveSignatories::find($item->id);
                if ($leaveSig != null) {
                    $leaveSig->Status = 'REJECTED';
                    $leaveSig->Notes = 'Auto rejected due to rejection of previous signatory.';
                    $leaveSig->save();
                }
            }
            
            // UPDATE LEAVE DAYS STATUS
            LeaveDays::where('LeaveId', $id)
                ->update(['Status' => 'REJECTED']);

            // INSERT SMS CODE HERE

            // INSERT NOTIF
            $user = Users::where('employee_id', $leaveApplication->EmployeeId)->first();
            $notifications = new Notifications;
            $notifications->UserId = $user != null ? $user->id : '';
            $notifications->Type = 'LEAVE_INFO';
            $notifications->Content = Users::find($leaveSignatory->EmployeeId)->name . ' rejected your leave application.';
            $notifications->Notes = $id;
            $notifications->Status = "UNREAD";
            $notifications->save();
            
            /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HR System - Leave Approval:\n\n" . Users::find($leaveSignatory->EmployeeId)->name . " has DISAPPROVED your " . $leaveApplication->LeaveType . " leave due to the following reasons:\n\n" . $notes,
                    "HR-Leave",
                    $id
                );
            }
        }

        return response()->json($leaveApplication, 200);
    }

    public function getLeavesByType(Request $request) {
        $employeeId = $request['EmployeeId'];
        $leaveType = $request['LeaveType'];

        $data = DB::table('LeaveApplications')
            ->whereRaw("LeaveApplications.EmployeeId='" . $employeeId . "' AND LeaveApplications.LeaveType='" . $leaveType . "'")
            ->select(
                'LeaveApplications.*',
                DB::raw("(SELECT SUM(Longevity) FROM LeaveDays WHERE LeaveId=LeaveApplications.id) AS TotalDays")
            )
            ->orderByDesc('LeaveApplications.created_at')
            ->get();

        return response()->json($data, 200);
    }

    public function manualEntries(Request $request) {
        $employees = Employees::orderBy('LastName')->get();

        $holidaysList = HolidaysList::whereRaw("HolidayDate > GETDATE()")->get();
        $holidays = "";
        $size = count($holidaysList);
        $i = 0;
        foreach($holidaysList as $item) {
            if ($i == ($size - 1)) {
                $holidays .= date('Y-m-d', strtotime($item->HolidayDate));
            } else {
                $holidays .= date('Y-m-d', strtotime($item->HolidayDate)) . ',';
            }
            $i++;
        }

        return view('/leave_applications/manual_entries', [
            'employees' => $employees,
            'holidays' => $holidays,
        ]);
    }

    public function getLeaveBalancesByEmployee(Request $request) {
        $data = LeaveBalances::where('EmployeeId', $request['EmployeeId'])
            ->first();

        $data->VacationExpanded = LeaveBalances::toExpanded($data->Vacation);
        $data->SickExpanded = LeaveBalances::toExpanded($data->Sick);
        $data->EmployeeData = Employees::find($request['EmployeeId']);

        return response()->json($data, 200);
    }

    public function manualSave(Request $request) {
        $employeeId = $request['EmployeeId'];
        $leaveType = $request['LeaveType'];
        $reason = $request['Reason'];
        $dateFiled = $request['DateFiled'];
        $days = $request['Days'];

        // insert leave application
        $id = IDGenerator::generateID();
        $leave = new LeaveApplications;
        $leave->id = $id;
        $leave->EmployeeId = $employeeId;
        $leave->Content = $reason;
        $leave->Status = 'APPROVED';
        $leave->LeaveType = $leaveType;
        $leave->created_at = $dateFiled;

        $totalCredits = 0;

        // insert days
        for($i=0; $i<count($days); $i++) {
            $totalMins = 0;
            $leaveDays = 0;
            
            $leaveDay = new LeaveDays;
            $leaveDay->id = IDGenerator::generateIDandRandString();
            $leaveDay->LeaveId = $id;
            $leaveDay->LeaveDate = $days[$i]['LeaveDate'];
            if ($days[$i]['Duration'] === 'WHOLE') {
                $leaveDay->Longevity = 1;

                // increment total minutes for vacation and sick leave
                if ($leaveType === 'Vacation' | $leaveType === 'Sick') {
                    $totalMins = (8 * 60);
                } else {
                    $leaveDays = 1;
                }
            } else {
                $leaveDay->Longevity = 0.5;

                // increment total minutes for vacation and sick leave
                if ($leaveType === 'Vacation' | $leaveType === 'Sick') {
                    $totalMins = (4 * 60);
                } else {
                    $leaveDays = .5;
                }
            }
            $leaveDay->Duration = $days[$i]['Duration'];
            $leaveDay->Status = 'APPROVED';
            $leaveDay->save();

            // update balance
            $leaveBalances = LeaveBalances::where('EmployeeId', $employeeId)->first();
            if ($leaveBalances != null) {
                if ($leaveType == 'Sick') {                        
                    $balance = floatval($leaveBalances->Sick);
                    $mins = $totalMins;

                    if ($balance < $mins) {
                        // save sobra nga leave as absent inside LeaveExcessAbsences
                        $excessInMins = ($mins - $balance);
                        $lea = new LeaveExcessAbsences;
                        $lea->id = IDGenerator::generateIDandRandString();
                        $lea->EmployeeId = $employeeId;
                        $lea->HoursAbsent = $excessInMins;
                        $lea->LeaveDate = $days[$i]['LeaveDate'];
                        $lea->Notes = 'Excess leave application (Sick leave)';
                        $lea->save();

                        $balance = 0;
                        $totalCredits += $balance;
                    } else {
                        $balance = $balance - $mins;
                        $totalCredits += $mins;
                    }                        

                    $leaveBalances->Sick = $balance;
                } elseif ($leaveType == 'Vacation') {
                    $balance = floatval($leaveBalances->Vacation);
                    $mins = $totalMins;
                    
                    if ($balance < $mins) {
                        // save sobra nga leave as absent inside LeaveExcessAbsences
                        $excessInMins = ($mins - $balance);
                        $lea = new LeaveExcessAbsences;
                        $lea->id = IDGenerator::generateIDandRandString();
                        $lea->EmployeeId = $employeeId;
                        $lea->HoursAbsent = $excessInMins;
                        $lea->LeaveDate = $days[$i]['LeaveDate'];
                        $lea->Notes = 'Excess leave application (Vacation leave)';
                        $lea->save();

                        $balance = 0;
                        $totalCredits += $balance;
                    } else {
                        $balance = $balance - $mins;
                        $totalCredits += $mins;
                    } 

                    $leaveBalances->Vacation = $balance;
                } elseif ($leaveType == 'Special') {
                    $balance = floatval($leaveBalances->Special);
                    $daysL = $leaveDays;
                    
                    if ($balance < $daysL) {
                        $balance = 0;

                        // save sobra nga leave as absent inside LeaveExcessAbsences
                        $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                        $lea = new LeaveExcessAbsences;
                        $lea->id = IDGenerator::generateIDandRandString();
                        $lea->EmployeeId = $employeeId;
                        $lea->HoursAbsent = $excessInMins;
                        $lea->LeaveDate = $days[$i]['LeaveDate'];
                        $lea->Notes = 'Excess leave application (Special leave)';
                        $lea->save();

                        $totalCredits += $balance;
                    } else {
                        $balance = $balance - $daysL;
                        $totalCredits += $daysL;
                    } 

                    $leaveBalances->Special = $balance;
                } elseif ($leaveType == 'Paternity') {
                    $balance = floatval($leaveBalances->Paternity);
                    $daysL = $leaveDays;
                    
                    if ($balance < $daysL) {
                        $balance = 0;

                        // save sobra nga leave as absent inside LeaveExcessAbsences
                        $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                        $lea = new LeaveExcessAbsences;
                        $lea->id = IDGenerator::generateIDandRandString();
                        $lea->EmployeeId = $employeeId;
                        $lea->HoursAbsent = $excessInMins;
                        $lea->LeaveDate = $days[$i]['LeaveDate'];
                        $lea->Notes = 'Excess leave application (Paternity leave)';
                        $lea->save();
                        
                        $totalCredits += $balance;
                    } else {
                        $balance = $balance - $daysL;
                        $totalCredits += $daysL;
                    } 

                    $leaveBalances->Paternity = $balance;
                } elseif ($leaveType == 'Maternity') {
                    $balance = floatval($leaveBalances->Maternity);
                    $daysL = $leaveDays;
                    
                    if ($balance < $daysL) {
                        $balance = 0;

                        // save sobra nga leave as absent inside LeaveExcessAbsences
                        $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                        $lea = new LeaveExcessAbsences;
                        $lea->id = IDGenerator::generateIDandRandString();
                        $lea->EmployeeId = $employeeId;
                        $lea->HoursAbsent = $excessInMins;
                        $lea->LeaveDate = $days[$i]['LeaveDate'];
                        $lea->Notes = 'Excess leave application (Maternity leave)';
                        $lea->save();
                        
                        $totalCredits += $balance;
                    } else {
                        $balance = $balance - $daysL;
                        $totalCredits += $daysL;
                    } 

                    $leaveBalances->Maternity = $balance;
                } elseif ($leaveType == 'MaternityForSoloMother') {
                    $balance = floatval($leaveBalances->MaternityForSoloMother);
                    $daysL = $leaveDays;
                    
                    if ($balance < $daysL) {
                        $balance = 0;

                        // save sobra nga leave as absent inside LeaveExcessAbsences
                        $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                        $lea = new LeaveExcessAbsences;
                        $lea->id = IDGenerator::generateIDandRandString();
                        $lea->EmployeeId = $employeeId;
                        $lea->HoursAbsent = $excessInMins;
                        $lea->LeaveDate = $days[$i]['LeaveDate'];
                        $lea->Notes = 'Excess leave application (Maternity For Solo Mother leave)';
                        $lea->save();
                        
                        $totalCredits += $balance;
                    } else {
                        $balance = $balance - $daysL;
                        $totalCredits += $daysL;
                    } 

                    $leaveBalances->MaternityForSoloMother = $balance;
                } elseif ($leaveType == 'SoloParent') {
                    $balance = floatval($leaveBalances->SoloParent);
                    $daysL = $leaveDays;
                    
                    if ($balance < $daysL) {
                        $balance = 0;
                        
                        // save sobra nga leave as absent inside LeaveExcessAbsences
                        $excessInMins = round(($daysL - $balance) * 8 * 60, 2);
                        $lea = new LeaveExcessAbsences;
                        $lea->id = IDGenerator::generateIDandRandString();
                        $lea->EmployeeId = $employeeId;
                        $lea->HoursAbsent = $excessInMins;
                        $lea->LeaveDate = $days[$i]['LeaveDate'];
                        $lea->Notes = 'Excess leave application (Solo Parent leave)';
                        $lea->save();
                        
                        $totalCredits += $balance;
                    } else {
                        $balance = $balance - $daysL;
                        $totalCredits += $daysL;
                    } 

                    $leaveBalances->SoloParent = $balance;
                }
                $leaveBalances->save();
            }
        }

        $leave->TotalCredits = $totalCredits;
        $leave->save();

        return response()->json($leave, 200);
    }

    public function publishLeave($id) {
        $leaveApplications = DB::table('LeaveApplications')
            ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
            ->select(
                'LeaveApplications.*',
                'FirstName',
                'LastName'
            )
            ->where('LeaveApplications.id', $id)
            ->first();

        $leaveDays = LeaveDays::where('LeaveId', $id)->orderBy('LeaveDate')->get();

        if (count($leaveDays) > 0) {
            $signatories = Employees::getSupers($leaveApplications->EmployeeId, ['Chief', 'Manager', 'General Manager']);

            if (count($signatories) > 0) {
                $employee = DB::table('users')
                    ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
                    ->whereRaw("users.id='" . $signatories[0]['id'] . "'")
                    ->first();

                if ($employee != null && $employee->ContactNumbers != null) {
                    SMSNotifications::sendSMS($employee->ContactNumbers, 
                        "HR System - Leave Approval:\n\n" . $leaveApplications->FirstName . " " . $leaveApplications->LastName . " has a new leave application that needs your approval.",
                        "HR-Leave",
                        $id
                    );
                }
            }
        }

        return redirect(route('home'));
    }

    public function fileForCoWorker(Request $request) {
        $employees = DB::table('LeaveUsersForOthers')
            ->leftJoin('Employees', 'LeaveUsersForOthers.EmployeeId', '=', 'Employees.id')
            ->whereRaw("LeaveUsersForOthers.LeaveCreator='" . Auth::user()->employee_id . "'")
            ->select('Employees.*')
            ->orderBy('LastName')
            ->get();

        $otherSignatories = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('users.id', 'Employees.id AS EmployeeId', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Level', 'Positions.Position', 'Positions.ParentPositionId', 'Positions.id AS PositionId')
            ->whereRaw("Positions.Level IN ('Supervisor', 'Chief', 'Manager', 'General Manager')")
            ->get();
    
        $holidaysList = HolidaysList::whereRaw("HolidayDate > GETDATE()")->get();
        $holidays = "";
        $size = count($holidaysList);
        $i = 0;
        foreach($holidaysList as $item) {
            if ($i == ($size - 1)) {
                $holidays .= date('Y-m-d', strtotime($item->HolidayDate));
            } else {
                $holidays .= date('Y-m-d', strtotime($item->HolidayDate)) . ',';
            }
            $i++;
        }

        return view('/leave_applications/file_for_coworker', [
            'employees' => $employees,
            'holidays' => $holidays,
            'otherSignatories' => $otherSignatories,
        ]);
    }

    public function saveForCoWorker(Request $request) {
        $employeeId = $request['EmployeeId'];
        $leaveType = $request['LeaveType'];
        $reason = $request['Reason'];
        $dateFiled = $request['DateFiled'];
        $days = $request['Days'];
        $signatories = $request['Signatories'];

        // insert leave application
        $id = IDGenerator::generateID();
        $leave = new LeaveApplications;
        $leave->id = $id;
        $leave->EmployeeId = $employeeId;
        $leave->Content = $reason;
        $leave->Status = 'Filed';
        $leave->LeaveType = $leaveType;
        $leave->created_at = $dateFiled;
        
        $totalCredits = 0;

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.*', 'Positions.Department', 'Positions.ParentPositionId', 'Positions.Level')
            ->whereRaw("Employees.id='" . $employeeId . "'")
            ->first();

        // INSERT LEAVE DAYS
        $smsDays = "";
        $totalDays = 0;
        for($i=0; $i<count($days); $i++) {
            $totalMins = 0;
            $leaveDays = 0;
            
            $leaveDay = new LeaveDays;
            $leaveDay->id = IDGenerator::generateIDandRandString();
            $leaveDay->LeaveId = $id;
            $leaveDay->LeaveDate = $days[$i]['LeaveDate'];
            if ($days[$i]['Duration'] === 'WHOLE') {
                $leaveDay->Longevity = 1;

                // increment total minutes for vacation and sick leave
                if ($leaveType === 'Vacation' | $leaveType === 'Sick') {
                    $totalMins = (8 * 60);

                    $totalCredits += $totalMins;
                } else {
                    $leaveDays = 1;

                    $totalCredits += $leaveDays;
                }
            } else {
                $leaveDay->Longevity = 0.5;

                // increment total minutes for vacation and sick leave
                if ($leaveType === 'Vacation' | $leaveType === 'Sick') {
                    $totalMins = (4 * 60);
                    
                    $totalCredits += $totalMins;
                } else {
                    $leaveDays = .5;
                    
                    $totalCredits += $leaveDays;
                }
            }
            $totalDays += 1;
            $leaveDay->Duration = $days[$i]['Duration'];
            $leaveDay->save();

            $smsDays .= date('D, M d, Y', strtotime($days[$i]['LeaveDate'])) . " (" . $days[$i]['Duration'] . ")" . "\n";
        }

        $leave->TotalCredits = $totalCredits;
        $leave->save();

        // INSERT SIGNATORIES
        if (isset($signatories)) {
            foreach($signatories as $item) {
                $sigs = new LeaveSignatories;
                $sigs->LeaveId = $id;
                $sigs->EmployeeId = $item['UserId'];
                $sigs->Rank = $item['Rank'];
                $sigs->save();

                // send sms if first signatory
                if ($item['Rank'] == '1' | $item['Rank'] == 1) {
                    $u = Users::find($item['UserId']);
                    if ($u != null) {
                        $fRank = Employees::find($u->employee_id);

                        // send notification
                        Notifications::create([
                            'UserId' => $u->id,
                            'Content' => $employee != null ? ($employee->FirstName . " " . $employee->LastName) : $u->name . " has filed a leave that needs your approval. ",
                            'Type' => 'LEAVE_APPROVAL',
                            'Notes' => $id,
                            'Status' => 'UNREAD',
                            'ForSignatory' => 'Yes',
                        ]);

                        if ($fRank != null && $fRank->ContactNumbers != null) {
                            SMSNotifications::sendSMS($fRank->ContactNumbers, 
                                "HRS Leave Approval\n\nHello " . $fRank->FirstName . ", " . $employee->FirstName . " " . $employee->LastName . " has filed a leave that needs your approval. " .
                                    "Kindly check your HR System approval module for more info.",
                                "HR-Leave",
                                $id
                            );
                        }
                    }                    
                }
            }
        }

        /**
         * =========================================================================
         * SEND SMS FOR FILEE
         * =========================================================================
         */
        if ($employee != null && $employee->ContactNumbers != null) {
            SMSNotifications::sendSMS($employee->ContactNumbers, 
                "HRS Leave Application\n\nHello " . $employee->FirstName . ", you have filed a leave with the following details:\n\n" .
                    "REASON: " . $reason . "\n" .
                    "DAYS:\n" . $smsDays . "\n" .
                    "TOTAL DAYS: " . $totalDays . "\n" .
                    "If this wasn't you, kindly inform the HR office for further checking.\nHave a great day!",
                "HR-Leave",
                $id
            );
        }

        return response()->json($leave, 200);
    }

    public function getSignatoriesForEmployee(Request $request) {
        $employeeId = $request['EmployeeId'];

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.id', '=', 'Employees.Designation')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Positions.Department', 'Positions.ParentPositionId', 'Positions.Level')
            ->whereRaw("Employees.id='" . $employeeId . "'")
            ->orderByDesc('EmployeesDesignations.created_at')
            ->first();

        $otherSignatories = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.id', '=', 'Employees.Designation')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('users.id', 'Employees.id AS EmployeeId', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Level', 'Positions.Position', 'Positions.ParentPositionId', 'Positions.id AS PositionId')
            ->whereRaw("Positions.Level IN ('Supervisor', 'Chief', 'Manager', 'General Manager')")
            ->get();

        $signatories = [];
        if ($employee != null) {
            if (in_array($employee->Level, ['Supervisor', 'Chief', 'Manager'])) {
                $signatories = Employees::getSupers($employeeId, ['Chief', 'Manager', 'General Manager']);
            } else {
                $signatories = Employees::getSupers($employeeId, ['Supervisor', 'Chief', 'Manager']);
            }
        }

        $data = [
            'Signatories' => $signatories,
            'OtherSignatories' => $otherSignatories,
        ];

        return response()->json($data, 200);
    }

    public function fileLeave(Request $request) {
        $employee = Employees::find(Auth::user()->employee_id);

        $holidaysList = HolidaysList::whereRaw("HolidayDate > GETDATE()")->get();
        $holidays = "";
        $size = count($holidaysList);
        $i = 0;
        foreach($holidaysList as $item) {
            if ($i == ($size - 1)) {
                $holidays .= date('Y-m-d', strtotime($item->HolidayDate));
            } else {
                $holidays .= date('Y-m-d', strtotime($item->HolidayDate)) . ',';
            }
            $i++;
        }

        if ($employee != null) {
            return view('/leave_applications/file_leave', [
                'holidays' => $holidays,
                'employee' => $employee
            ]);
        } else {
            return abort(403, 'You are not authorized to access this module');
        }
    }

    public function viewAllLeave(Request $request) {
        return view('/leave_applications/all_leave', []);
    }

    public function searchLeave(Request $request) {
        $params = $request['search'];
        $type = $request['type'];

        if (isset($params)) {
            if ($type === 'All') {
                $data = DB::table('LeaveApplications')
                    ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                    ->where('Employees.FirstName', 'LIKE', '%' . $params . '%')
                    ->orWhere('Employees.MiddleName', 'LIKE', '%' . $params . '%')
                    ->orWhere('Employees.LastName', 'LIKE', '%' . $params . '%')
                    ->orWhere('Employees.id', 'LIKE', '%' . $params . '%')
                    ->select(
                        'LeaveApplications.*',
                        'Employees.FirstName',
                        'Employees.LastName',
                        'Employees.MiddleName',
                        'Employees.Suffix',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();
            } else {
                $data = DB::table('LeaveApplications')
                    ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                    ->where('LeaveType', $type)
                    ->whereRaw("(Employees.FirstName LIKE '%" . $params . "%' OR Employees.MiddleName LIKE '%" . $params . "%' OR Employees.LastName LIKE '%" . $params . "%' OR Employees.id LIKE '%" . $params . "%')")
                    ->select(
                        'LeaveApplications.*',
                        'Employees.FirstName',
                        'Employees.LastName',
                        'Employees.MiddleName',
                        'Employees.Suffix',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();
            }
        } else {
            if ($type === 'All') {
                $data = DB::table('LeaveApplications')
                    ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                    ->select(
                        'LeaveApplications.*',
                        'Employees.FirstName',
                        'Employees.LastName',
                        'Employees.MiddleName',
                        'Employees.Suffix',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();
            } else {
                $data = DB::table('LeaveApplications')
                    ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                    ->where('LeaveType', $type)
                    ->select(
                        'LeaveApplications.*',
                        'Employees.FirstName',
                        'Employees.LastName',
                        'Employees.MiddleName',
                        'Employees.Suffix',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();
            }
        }

        foreach($data as $item) {
            $item->Days = DB::table('LeaveDays')
                ->where('LeaveId', $item->id)
                ->get();
        }

        $data = IDGenerator::paginate($data, 16);

        return response()->json($data, 200);
    }

    public function getLeaveByEmployee(Request $request) {
        $employeeId = $request['EmployeeId'];

        $data = DB::table('LeaveApplications')
                    ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                    ->where('EmployeeId', $employeeId)
                    ->select(
                        'LeaveApplications.*',
                        'Employees.FirstName',
                        'Employees.LastName',
                        'Employees.MiddleName',
                        'Employees.Suffix',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();

        foreach($data as $item) {
            $item->Days = DB::table('LeaveDays')
                ->where('LeaveId', $item->id)
                ->get();
        }

        $data = IDGenerator::paginate($data, 15);

        return response()->json($data, 200);
    }

    public function leaveReport(Request $request) {
        return view('/leave_applications/leave_report', []);
    }

    public function getLeaveReport(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $type = $request['Type'];

        if ($type === 'All') {
            $data = DB::table('LeaveApplications')
                ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                ->whereRaw("(LeaveApplications.created_at BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'LeaveApplications.*',
                    'Employees.FirstName',
                    'Employees.LastName',
                    'Employees.MiddleName',
                    'Employees.Suffix',
                )
                ->orderByDesc('LeaveApplications.created_at')
                ->get();
        } else {
            $data = DB::table('LeaveApplications')
                ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                ->where('LeaveType', $type)
                ->whereRaw("(LeaveApplications.created_at BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'LeaveApplications.*',
                    'Employees.FirstName',
                    'Employees.LastName',
                    'Employees.MiddleName',
                    'Employees.Suffix',
                )
                ->orderByDesc('LeaveApplications.created_at')
                ->get();
        }

        foreach($data as $item) {
            $item->Days = DB::table('LeaveDays')
                ->where('LeaveId', $item->id)
                ->get();
        }

        $data = IDGenerator::paginate($data, 50);

        return response()->json($data, 200);
    }

    public function printLeaveReport($from, $to, $type) {
        if ($type === 'All') {
            $data = DB::table('LeaveApplications')
                ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                ->whereRaw("(LeaveApplications.created_at BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'LeaveApplications.*',
                    'Employees.FirstName',
                    'Employees.LastName',
                    'Employees.MiddleName',
                    'Employees.Suffix',
                )
                ->orderByDesc('LeaveApplications.created_at')
                ->get();
        } else {
            $data = DB::table('LeaveApplications')
                ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
                ->where('LeaveType', $type)
                ->whereRaw("(LeaveApplications.created_at BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'LeaveApplications.*',
                    'Employees.FirstName',
                    'Employees.LastName',
                    'Employees.MiddleName',
                    'Employees.Suffix',
                )
                ->orderByDesc('LeaveApplications.created_at')
                ->get();
        }

        foreach($data as $item) {
            $item->Days = DB::table('LeaveDays')
                ->where('LeaveId', $item->id)
                ->get();
        }

        return view('/leave_applications/print_leave_report', [
            'from' => $from,
            'to' => $to,
            'type' => $type,
            'data' => $data,
        ]);
    }
}
