<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveApplicationsRequest;
use App\Http\Requests\UpdateLeaveApplicationsRequest;
use App\Repositories\LeaveApplicationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\LeaveApplications;
use App\Models\LeaveSignatories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
            ->select('LeaveSignatories.id', 'LeaveSignatories.EmployeeId', 'LeaveSignatories.Status', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Position')
            ->where('LeaveSignatories.LeaveId', $id)
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

        return redirect(route('leaveApplications.index'));
    }

    public function createStepTwo($id) {
        $leaveApplication = LeaveApplications::find($id);

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Positions.Department')
            ->whereRaw("Employees.id='" . $leaveApplication->EmployeeId . "'")
            ->first();

        $users = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('users.id', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix')
            ->whereRaw("Positions.Department='" . $employee->Department . "' AND Positions.Level IN ('Managerial', 'Chief', 'Supervisor')")
            ->get();

        $leaveSignatories = DB::table('LeaveSignatories')
            ->leftJoin('users', 'LeaveSignatories.EmployeeId', '=', 'users.id')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('LeaveSignatories.id', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Position')
            ->where('LeaveSignatories.LeaveId', $id)
            ->get();

        $leaveDays = LeaveDays::where('LeaveId', $id)->orderBy('LeaveDate')->get();

        $leaveBalance = DB::table('LeaveBalances')
            ->select(DB::raw($leaveApplication->LeaveType . ' AS Balance'))
            ->where('EmployeeId', $leaveApplication->EmployeeId)
            ->first();

        $leaveImgs = LeaveImageAttachments::where('LeaveId', $id)->get();

        return view('/leave_applications/create_step_two', [
            'leaveApplication' => $leaveApplication,
            'users' => $users,
            'leaveSignatories' => $leaveSignatories,
            'leaveDays' => $leaveDays,
            'leaveBalance' => $leaveBalance,
            'leaveImgs' => $leaveImgs,
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
            ->get();

        return view('/leave_applications/approvals',[
            'leaveApplication' => $leaveApplication,
            'leaveSignatories' => $leaveSignatories,
        ]);
    }

    public function approveLeave($id, $signatoryId) {        
        $leaveApplication = LeaveApplications::find($id);
        $leaveSignatory = LeaveSignatories::find($signatoryId);

        // UPDATE LEAVE STATUS
        $leaveApplication->Status = 'Partially Approved';
        $leaveApplication->save();

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
        $nextSignatory = LeaveSignatories::where('LeaveId', $id)->where('Rank', intval($leaveSignatory->Rank)+1)->first();

        if ($nextSignatory != null) {
            // IF LEAVE IS STILL NOTE COMPLETED SIGNING
            $notifications = new Notifications;
            $notifications->UserId = $nextSignatory->EmployeeId;
            $notifications->Type = 'LEAVE_APPROVAL';
            $notifications->Content = Employees::getMergeName(Employees::find($leaveApplication->EmployeeId)) . " has filed a leave. Check it out to approve.";
            $notifications->Notes = $id;
            $notifications->Status = "UNREAD";
            $notifications->save();
        } else {
            // UPDATE LEAVE STATUS
            // THIS PORTION IS WHEN THE LEAVE HAS FULLY SIGNED BY ALL SIGNATORIES
            /**
             * FILTER IF SICK LEAVE, NEEDS TO BE APPROVED BY HR
            */
            if ($leaveApplication->LeaveType == 'Sick') {
                $leaveApplication->Status = 'FOR REVIEW';
                $leaveApplication->save();
            } else {
                $leaveApplication->Status = 'APPROVED';
                $leaveApplication->save();

                // PLOT LEAVE DAYS TO ATTENDANCE DATA
                $leaveDays = LeaveDays::where('LeaveId', $id)->get();
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
                    }
                }    
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

        // UPDATE LEAVE STATUS
        $leaveApplication->Status = 'Partially Approved';
        $leaveApplication->save();

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
        $nextSignatory = LeaveSignatories::where('LeaveId', $id)->where('Rank', intval($leaveSignatory->Rank)+1)->first();

        if ($nextSignatory != null) {
            // IF LEAVE IS STILL NOTE COMPLETED SIGNING
            $notifications = new Notifications;
            $notifications->UserId = $nextSignatory->EmployeeId;
            $notifications->Type = 'LEAVE_APPROVAL';
            $notifications->Content = Employees::getMergeName(Employees::find($leaveApplication->EmployeeId)) . " has filed a leave. Check it out to approve.";
            $notifications->Notes = $id;
            $notifications->Status = "UNREAD";
            $notifications->save();
        } else {
            // UPDATE LEAVE STATUS
            // THIS PORTION IS WHEN THE LEAVE HAS FULLY SIGNED BY ALL SIGNATORIES
            /**
             * FILTER IF SICK LEAVE, NEEDS TO BE APPROVED BY HR
            */
            if ($leaveApplication->LeaveType == 'Sick') {
                $leaveApplication->Status = 'FOR REVIEW';
                $leaveApplication->save();
            } else {
                $leaveApplication->Status = 'APPROVED';
                $leaveApplication->save();

                // PLOT LEAVE DAYS TO ATTENDANCE DATA
                $leaveDays = LeaveDays::where('LeaveId', $id)->get();
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
                    }
                }    
            }                    
        }
        
        return response()->json('ok', 200);
    }

    public function deleteLeave(Request $request) {
        $id = $request['id'];

        LeaveApplications::find($id)->delete();

        LeaveDays::where('LeaveId', $id)->delete();

        LeaveImageAttachments::where('LeaveId', $id)->delete();

        LeaveSignatories::where('LeaveId', $id)->delete();

        return response()->json('ok', 200);
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
}
