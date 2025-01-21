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

class EmployeeInfo extends Controller {
    public function getEmployeeInformation(Request $request) {
        $id = $request['employee_id'];

        if (isset($id)) {
            $employee = Employees::find($id);
            $leaveBalances = LeaveBalances::where('EmployeeId', $id)->first();

            if ($leaveBalances != null) {
                $leaveBalances->VacationArray = LeaveBalances::toBalanceAssocArray($leaveBalances->Vacation);
                $leaveBalances->SickArray = LeaveBalances::toBalanceAssocArray($leaveBalances->Sick);
            }
            $data = [
                'Employee' => $employee,
                'LeaveBalances' => $leaveBalances,
            ];

            return response()->json($data, 200);
        } else {
            return response()->json([], 404);
        }
    }

    public function getAttendanceData(Request $request) {
        $id = $request['employee_id'];

        if (isset($id)) {
            $employee = Employees::find($id);

            $attendanceData = AttendanceData::where('BiometricUserId', $employee != null && $employee->BiometricsUserId != null ? $employee->BiometricsUserId : '')
                // ->whereNull('AbsentPermission')
                ->orderBy('Timestamp')
                ->get();

            return response()->json($attendanceData, 200);
        } else {
            return response()->json([], 404);
        }
    }
}
