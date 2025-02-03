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

    public function getSignatories(Request $request) {
        $id = $request['id'];

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Positions.Department', 'Positions.ParentPositionId', 'Positions.Level')
            ->whereRaw("Employees.id='" . $id . "'")
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
                $signatories = Employees::getSupers($id, ['Chief', 'Manager', 'General Manager']);
            } else {
                $signatories = Employees::getSupers($id, ['Chief', 'Manager']);
            }
        }

        return response()->json(
            [
                'Signatories' => $signatories,
                'OtherSignatories' => $otherSignatories,
            ], 200
        );
    }
}
