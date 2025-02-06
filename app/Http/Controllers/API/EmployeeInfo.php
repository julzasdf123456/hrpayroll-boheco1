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
use App\Models\EmployeesDesignations;
use App\Models\Positions;

class EmployeeInfo extends Controller {
    public function getEmployeeInformation(Request $request) {
        $id = $request['employee_id'];

        if (isset($id)) {
            $employee = DB::table('Employees')
                ->leftJoin('Towns', 'Employees.TownCurrent', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Employees.BarangayCurrent', '=', 'Barangays.id')
                ->whereRaw("Employees.id='". $id ."'")
                ->select(
                    'Employees.id',
                    'Employees.FirstName',
                    'Employees.MiddleName',
                    'Employees.LastName',
                    'Employees.Suffix',
                    'Employees.Gender',
                    'Employees.Birthdate',
                    'Employees.StreetCurrent',
                    DB::raw("Barangays.Barangays as BarangayCurrent"),
                    DB::raw("Towns.Town as TownCurrent"),
                    'Employees.ProvinceCurrent',
                    'Employees.StreetPermanent',
                    'Employees.BarangayPermanent',
                    'Employees.TownPermanent',
                    'Employees.ProvincePermanent',
                    'Employees.ContactNumbers',
                    'Employees.EmailAddress',
                    'Employees.BloodType',
                    'Employees.CivilStatus',
                    'Employees.Religion',
                    'Employees.Citizenship',
                    'Employees.Designation',
                    'Employees.BiometricsUserId',
                    'Employees.PayrollScheduleId',
                    'Employees.AuthorizedToDrive',
                    'Employees.NoAttendanceAllowed',
                    'Employees.DayOffDates',
                    'Employees.Longevity',
                    'Employees.OfficeDesignation',
                    'Employees.DateHired',
                    'Employees.EmploymentStatus',
                    'Employees.DateEnded',
                    'Employees.PrimaryBankNumber',
                    'Employees.PrimaryBank',
                    'Employees.TIN',
                    'Employees.SSSNumber',
                    'Employees.PhilHealthNumber',
                    'Employees.PagIbigNumber',
                    'Employees.PositionStatus',
                    'Employees.SoloMother',
                    'Employees.SoloParent',
                    'Employees.Mother',
                    'Employees.Father',
                    'Employees.ProfilePicture',
                )
                ->first();
            $designation = EmployeesDesignations::find($employee->Designation);
            $position = $designation != null ? Positions::find($designation->PositionId) : null;
            $leaveBalances = LeaveBalances::where('EmployeeId', $id)->first();

            if ($leaveBalances != null) {
                $leaveBalances->VacationArray = LeaveBalances::toBalanceAssocArray($leaveBalances->Vacation);
                $leaveBalances->SickArray = LeaveBalances::toBalanceAssocArray($leaveBalances->Sick);
            }
            $data = [
                'Employee' => $employee,
                'Designation' => $designation,
                'Position' => $position,
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
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.id', '=', 'Employees.Designation')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Positions.Department', 'Positions.ParentPositionId', 'Positions.Level')
            ->whereRaw("Employees.id='" . $id . "'")
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
                $signatories = Employees::getSupers($id, ['Chief', 'Manager', 'General Manager']);
            } else {
                $signatories = Employees::getSupers($id, ['Supervisor', 'Chief', 'Manager']);
            }
        }

        return response()->json(
            [
                'Signatories' => $signatories,
                'OtherSignatories' => $otherSignatories,
            ], 200
        );
    }

    public function getMyApprovals(Request $request) {
        $userId = $request['UserId'];

        $leaves = DB::table('LeaveSignatories')
            ->leftJoin('LeaveApplications', 'LeaveSignatories.LeaveId', '=', 'LeaveApplications.id')
            ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
            ->whereRaw("LeaveSignatories.EmployeeId='" . $userId . "' AND LeaveSignatories.Status IS NULL AND LeaveSignatories.id IN 
                (SELECT TOP 1 x.id FROM LeaveSignatories x WHERE x.LeaveId=LeaveSignatories.LeaveId AND x.Status IS NULL ORDER BY x.Rank)")
            ->select(
                DB::raw("TRY_CAST(LeaveApplications.id AS VARCHAR) AS id"),
                DB::raw("TRY_CAST(LeaveSignatories.id AS VARCHAR) AS SignatoryId"),
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                'LeaveApplications.LeaveType AS SubType',
                DB::raw("'Leave' AS Type"),
                'LeaveApplications.Content AS MainContent',
                DB::raw('NULL AS SubContent'),
                'LeaveApplications.Status',
                'LeaveApplications.created_at'
            );

        $offsets = DB::table('OffsetSignatories')
            ->leftJoin('OffsetApplications', 'OffsetSignatories.OffsetBatchId', '=', 'OffsetApplications.OffsetBatchId')
            ->leftJoin('users', 'OffsetApplications.PreparedBy', '=', 'users.id')
            ->leftJoin('Employees', 'OffsetApplications.EmployeeId', '=', 'Employees.id')
            ->whereRaw("OffsetSignatories.EmployeeId='" . $userId . "' AND (OffsetApplications.Status IS NULL OR OffsetApplications.Status NOT IN ('APPROVED', 'REJECTED')) AND OffsetSignatories.id IN 
                (SELECT TOP 1 x.id FROM OffsetSignatories x WHERE x.OffsetBatchId=OffsetSignatories.OffsetBatchId AND x.Status IS NULL ORDER BY x.Rank)")
            ->select(
                DB::raw("TRY_CAST(OffsetApplications.id AS VARCHAR) AS id"),
                DB::raw("TRY_CAST(OffsetSignatories.id AS VARCHAR) AS SignatoryId"),
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                DB::raw('OffsetApplications.Duration AS SubType'),
                DB::raw("'Offset' AS Type"),
                'OffsetApplications.PurposeOfDuty AS MainContent',
                'OffsetApplications.OffsetReason AS SubContent',
                'OffsetApplications.Status',
                'OffsetApplications.created_at'
            )
            ->union($leaves);

        $attConfs = DB::table('AttendanceConfirmationSignatories')
            ->leftJoin('AttendanceConfirmations', 'AttendanceConfirmationSignatories.AttendanceConfirmationId', '=', 'AttendanceConfirmations.id')
            ->leftJoin('users', 'AttendanceConfirmations.UserId', '=', 'users.id')
            ->leftJoin('Employees', 'AttendanceConfirmations.EmployeeId', '=', 'Employees.id')
            ->whereRaw("AttendanceConfirmationSignatories.EmployeeId='" . $userId . "' AND (AttendanceConfirmations.Status IS NULL OR AttendanceConfirmations.Status NOT IN ('APPROVED', 'REJECTED')) AND AttendanceConfirmationSignatories.id IN 
                (SELECT TOP 1 x.id FROM AttendanceConfirmationSignatories x WHERE x.AttendanceConfirmationId=AttendanceConfirmationSignatories.AttendanceConfirmationId AND x.Status IS NULL ORDER BY x.Rank)")
            ->select(
                DB::raw("TRY_CAST(AttendanceConfirmations.id AS VARCHAR) AS id"),
                DB::raw("TRY_CAST(AttendanceConfirmationSignatories.id AS VARCHAR) AS SignatoryId"),
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                DB::raw('NULL AS SubType'),
                DB::raw("'Attendance Confirmation' AS Type"),
                'AttendanceConfirmations.Reason AS MainContent',
                DB::raw('NULL AS SubContent'),
                'AttendanceConfirmations.Status',
                'AttendanceConfirmations.created_at'
            )
            ->union($offsets);

        $tripTickets = DB::table('TripTickets')
            ->leftJoin(DB::raw("Employees AS e"), DB::raw("TRY_CAST(TripTickets.EmployeeId AS VARCHAR)"), '=', DB::raw("e.id"))
            ->leftJoin('TripTicketSignatories', DB::raw("TRY_CAST(TripTickets.id AS VARCHAR)"), '=', DB::raw("TRY_CAST(TripTicketSignatories.TripTicketId AS VARCHAR)"))
            ->whereRaw("TripTicketSignatories.EmployeeId='" . $userId . "' AND TripTickets.Status NOT IN ('Trash', 'APPROVED', 'REJECTED', 'DEPARTED', 'ARRIVED')")
            ->select(
                DB::raw("TRY_CAST(TripTickets.id AS VARCHAR) AS id"),
                DB::raw("TRY_CAST(TripTicketSignatories.id AS VARCHAR) AS SignatoryId"),
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
                DB::raw('NULL AS SubType'),
                DB::raw("'Trip Ticket' AS Type"),
                'TripTickets.PurposeOfTravel AS MainContent',
                'TripTickets.Vehicle AS SubContent',
                'TripTickets.Status',
                'TripTickets.created_at'
            )
            ->union($attConfs)
            ->orderBy('created_at')
            ->get();

        return response()->json($tripTickets, 200);
    }

    public function getEmployeeDesignations(Request $request) {
        $id = $request['EmployeeId'];

        $designations = DB::table('EmployeesDesignations')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->whereRaw("EmployeesDesignations.EmployeeId='" . $id . "'")
            ->select(
                'EmployeesDesignations.id',
                'EmployeesDesignations.EmployeeId',
                DB::raw("Positions.Position AS PositionId"),
                'EmployeesDesignations.Description',
                'EmployeesDesignations.DateStarted',
                'EmployeesDesignations.DateEnd',
                'EmployeesDesignations.SalaryGrade',
                'EmployeesDesignations.SalaryAmount',
                'EmployeesDesignations.SalaryAddOns',
                'EmployeesDesignations.Status',
                'EmployeesDesignations.IsActive',
                DB::raw("Positions.Department AS SubjectLoad"),
                'EmployeesDesignations.created_at',
                'EmployeesDesignations.updated_at',
            )
            ->orderByDesc('EmployeesDesignations.DateStarted')
            ->get();

        return response()->json($designations, 200);
    }

    public function uploadProfileImage(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:60048',
        ]);

        // Store the uploaded image in the 'public/uploads' directory
        if ($request->file('image')) {
            $image = $request->file('image');
            $imageName = $request['employeeId'] . '.jpg';
            $image->move(public_path() . "/imgs/profiles/", $imageName);
            
            // update empoyees
            Employees::where('id', $request['employeeId'])
                ->update(['ProfilePicture' => $request['employeeId'] . ".jpg"]);

            return response()->json(['success' => 'Image uploaded successfully!', 'image' => $imageName]);
        }

        return response()->json(['error' => 'Image upload failed'], 400);
    }
}
