<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\Employees;
use App\Models\LeaveBalances;
use App\Models\AttendanceData;
use App\Models\SMSNotifications;
use App\Models\Notifications;
use App\Models\LeaveApplications;
use App\Models\LeaveDays;
use App\Models\LeaveSignatories;
use App\Models\Users;
use App\Models\LeaveBalanceDetails;
use App\Models\PayrollSchedules;
use App\Models\LeaveExcessAbsences;

class Leave extends Controller {
    public function getLeaveSignatories(Request $request) {
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

    public function postLeave(Request $request) {
        $validated = $request->validate([
            'EmployeeId' => 'required|string',
            'LeaveType' => 'required|string',
            'Reason' => 'required|string',
            'DateFiled' => 'required|string',
            'Days' => 'required|array',
            'Signatories' => 'required|array'
        ]);

        $employeeId = $validated['EmployeeId'];
        $leaveType = $validated['LeaveType'];
        $reason = $validated['Reason'];
        $dateFiled = $validated['DateFiled'];
        $days = $validated['Days'];
        $signatories = $validated['Signatories'];

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

                // send notifications if first signatory
                if ($item['Rank'] == '1' | $item['Rank'] == 1) {
                    $u = Users::find($item['UserId']);
                    if ($u != null) {
                        $fRank = Employees::find($u->employee_id);

                        // send notification
                        Notifications::create([
                            'UserId' => $u->id,
                            'Content' => ($employee != null ? ($employee->FirstName . " " . $employee->LastName) : $u->name) . " has filed a leave that needs your approval. ",
                            'Type' => 'LEAVE_APPROVAL',
                            'Notes' => $id,
                            'Status' => 'UNREAD',
                            'ForSignatory' => 'Yes',
                        ]);

                        // send sms
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
        return response()->json([], 200);
    }

    public function getAllLeave(Request $request) {
        $employeeId = $request['EmployeeId'];
        $type = $request['Type'];
        $search = $request['Search'];

        if ($type === 'All') {
            if ($search != null) {
                $data = DB::table('LeaveApplications')
                    ->where('EmployeeId', $employeeId)
                    ->whereRaw("Content LIKE '%" . $search . "%'")
                    ->select(
                        'LeaveApplications.*',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();
            } else {
                $data = DB::table('LeaveApplications')
                    ->where('EmployeeId', $employeeId)
                    ->select(
                        'LeaveApplications.*',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();
            }
        } else {
            if ($search != null) {
                $data = DB::table('LeaveApplications')
                    ->where('EmployeeId', $employeeId)
                    ->whereRaw("Content LIKE '%" . $search . "%' AND LeaveType='" . $type . "'")
                    ->select(
                        'LeaveApplications.*',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();
            } else {
                $data = DB::table('LeaveApplications')
                    ->where('EmployeeId', $employeeId)
                    ->whereRaw("LeaveType='" . $type . "'")
                    ->select(
                        'LeaveApplications.*',
                    )
                    ->orderByDesc('LeaveApplications.created_at')
                    ->get();
            }
        }

        foreach($data as $item) {
            $item->Days = DB::table('LeaveDays')
                ->where('LeaveId', $item->id)
                ->get();
            $item->Employee = null;
        }

        return response()->json($data, 200);
    }

    public function deleteLeave(Request $request) {
        $id = $request['id'];

        $leave = LeaveApplications::find($id);

        if ($leave != null) {
            // only allow deletes on leave that has a 'Filed' status
            if ($leave->Status !== 'APPROVED' | $leave->Status !== 'Approved') {
                LeaveDays::where('LeaveId', $id)->delete();
                LeaveSignatories::where('LeaveId', $id)->delete();
                Notifications::where('Notes', $id)->delete();
                
                $leave->delete();

                return response()->json(['res' => 'ok'], 200);
            } else {
                return response()->json('Not allowed', 403);
            }
        } else {
            return response()->json('No leave found', 404);
        }
    }

    public function getLeaveCreditLogs(Request $request) {
        $id = $request['id'];
        
        $leaveBalanceDetails = LeaveBalanceDetails::where('EmployeeId', $id)->orderByDesc('created_at')->get();

        return response()->json($leaveBalanceDetails, 200);
    }

    public function getLeave(Request $request) {
        $id = $request['id'];

        $data = LeaveApplications::find($id);

        if ($data != null) {
            $data->Employee = Employees::find($data->EmployeeId);

            $data->Days = DB::table('LeaveDays')
                ->where('LeaveId', $data->id)
                ->get();

            $data->Signatories = DB::table('LeaveSignatories')
                ->leftJoin("users", "LeaveSignatories.EmployeeId", "=", 'users.id')
                ->select(
                    "LeaveSignatories.*",
                    "users.name"
                )
                ->where('LeaveId', $data->id)
                ->orderBy("Rank")
                ->get();

            return response()->json($data, 200);
        } else {
            return response()->json('leave not found', 404);
        }
    }

    public function approveLeave(Request $request) {
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

    public function rejectLeave(Request $request) {
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
            $notifications->Content = Users::find($leaveSignatory->EmployeeId)->name . ' REJECTED your leave application.';
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
}
