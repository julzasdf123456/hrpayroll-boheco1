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
use App\Models\LeaveApplications;
use App\Models\LeaveDays;
use App\Models\LeaveSignatories;
use App\Models\Users;

class Leave extends Controller {
    public function getLeaveSignatories(Request $request) {
        $employeeId = $request['EmployeeId'];

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Positions.Department', 'Positions.ParentPositionId', 'Positions.Level')
            ->whereRaw("Employees.id='" . $employeeId . "'")
            ->first();

        $otherSignatories = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('users.id', 'Employees.id AS EmployeeId', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Level', 'Positions.Position', 'Positions.ParentPositionId', 'Positions.id AS PositionId')
            ->whereRaw("Positions.Level IN ('Supervisor', 'Chief', 'Manager', 'General Manager')")
            ->get();

        $signatories = [];
        if ($employee != null) {
            if (in_array($employee->Level, ['Supervisor', 'Chief', 'Manager'])) {
                $signatories = Employees::getSupers($employeeId, ['Chief', 'Manager', 'General Manager']);
            } else {
                $signatories = Employees::getSupers($employeeId, ['Chief', 'Manager']);
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
        $leave->save();

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
            
            $leaveDay = new LeaveDays;
            $leaveDay->id = IDGenerator::generateIDandRandString();
            $leaveDay->LeaveId = $id;
            $leaveDay->LeaveDate = $days[$i]['LeaveDate'];
            if ($days[$i]['Duration'] === 'WHOLE') {
                $leaveDay->Longevity = 1;
                $totalDays += 1;
            } else {
                $leaveDay->Longevity = 0.5;
                $totalDays += 0.5;
            }
            $leaveDay->Duration = $days[$i]['Duration'];
            $leaveDay->save();

            $smsDays .= date('D, M d, Y', strtotime($days[$i]['LeaveDate'])) . " (" . $days[$i]['Duration'] . ")" . "\n";
        }

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
}
