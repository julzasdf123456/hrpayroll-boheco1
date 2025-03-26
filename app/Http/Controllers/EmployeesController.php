<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeesRequest;
use App\Http\Requests\UpdateEmployeesRequest;
use App\Repositories\EmployeesRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\Towns;
use App\Models\EmployeesDesignations;
use App\Models\Rankings;
use App\Models\RankingRepository;
use App\Models\Employees;
use App\Models\EducationalAttainment;
use App\Models\LeaveApplications;
use App\Models\LeaveDays;
use App\Models\Users;
use App\Models\User;
use App\Models\ProfessionalIDs;
use App\Models\LeaveAttendanceDates;
use App\Models\EmployeeImages;
use App\Models\IDGenerator;
use App\Models\AttendanceData;
use App\Models\EmployeePayrollSchedules;
use App\Models\PayrollSchedules;
use App\Models\LeaveBalances;
use App\Models\LeaveBalanceDetails;
use App\Models\OffsetApplications;
use App\Models\Overtimes;
use App\Models\EmployeePayrollSundries;
use App\Models\EmployeeDayOffs;
use App\Models\LeaveExcessAbsences;
use App\Models\AttendaneConfirmations;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\File;
use Flash;
use Response;
use File;

class EmployeesController extends AppBaseController
{
    /** @var  EmployeesRepository */
    private $employeesRepository;

    public function __construct(EmployeesRepository $employeesRepo)
    {
        $this->employeesRepository = $employeesRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the Employees.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (Permission::hasDirectPermission(['god permission', 'view employee'])) {
            $employees = $this->employeesRepository->all();

            return view('employees.index')
                ->with('employees', $employees);
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    /**
     * Show the form for creating a new Employees.
     *
     * @return Response
     */
    public function create()
    {
        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        return view('employees.create', ['towns' => $towns]);
    }

    /**
     * Store a newly created Employees in storage.
     *
     * @param CreateEmployeesRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeesRequest $request)
    {
        $input = $request->all();
        $input['FirstName'] = strtoupper($input['FirstName']);
        $input['LastName'] = strtoupper($input['LastName']);
        $input['MiddleName'] = strtoupper($input['MiddleName']);
        $input['Suffix'] = strtoupper($input['Suffix']);
        $input['ProvinceCurrent'] = strtoupper($input['ProvinceCurrent']);
        $input['ProvincePermanent'] = strtoupper($input['ProvincePermanent']);
        $input['StreetPermanent'] = strtoupper($input['StreetPermanent']);
        $input['StreetCurrent'] = strtoupper($input['StreetCurrent']);
        $employees = $this->employeesRepository->create($input);

        Flash::success('Employees saved successfully.');

        return redirect(route('employees.create-designations', [$employees->id]));
    }

    /**
     * Display the specified Employees.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        if (Permission::hasDirectPermission(['god permission', 'view employee'])) {
            $employees = $this->employeesRepository->find($id);
            $employeeDesignations = DB::table('EmployeesDesignations')
                ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
                ->select('EmployeesDesignations.*', 'Positions.Position')
                ->where('EmployeesDesignations.EmployeeId', $id)
                ->orderByDesc('EmployeesDesignations.created_at')
                ->get();
            $rankings = DB::table('Rankings')
                ->leftJoin('RankingRepository', 'Rankings.RankingRepositoryId', '=', 'RankingRepository.id')
                ->where('Rankings.EmployeeId', $id)
                ->select('Rankings.id', 'RankingRepository.Type', 'RankingRepository.RankingName', 'Rankings.Points')
                ->get();
            $educationalAttainment = EducationalAttainment::where('EmployeeId', $id)->get();        
            $ids = ProfessionalIDs::where('EmployeeId', $id)->get();
            $payslips = DB::table('PayrollDetails')
                ->leftJoin('PayrollIndex', 'PayrollDetails.PayrolIndexId', '=', 'PayrollIndex.id')
                ->select('PayrollIndex.SalaryPeriod', 
                        'PayrollIndex.DateFrom',
                        'PayrollIndex.DateTo',
                        'PayrollDetails.id')
                ->where('PayrollDetails.EmployeeId', $id)
                ->get();
            $workSchedules = PayrollSchedules::find($employees->PayrollScheduleId);
            // $workSchedules = null;
            $leaveBalance = LeaveBalances::where('EmployeeId', $id)->first();
            $leaveBalanceDetails = LeaveBalanceDetails::where('EmployeeId', $id)->orderByDesc('created_at')->get();

            $tripTickets = DB::table('TripTickets')
                ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
                ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
                ->whereRaw("TripTickets.EmployeeId='" . $id . "' AND TripTickets.Status NOT IN ('Trash')")
                ->select(
                    'TripTickets.*',
                    'Employees.FirstName AS DriverFirstName',
                    'Employees.MiddleName AS DriverMiddleName',
                    'Employees.LastName AS DriverLastName',
                    'Employees.Suffix AS DriverSuffix',
                    'e.FirstName',
                    'e.MiddleName',
                    'e.LastName',
                    'e.Suffix',
                )
                ->orderByDesc('DatetimeFiled')
                ->get();

            $travelOrders = DB::table('TravelOrderEmployees')
                ->leftJoin('TravelOrders', 'TravelOrderEmployees.TravelOrderId', '=', 'TravelOrders.id')
                ->whereRaw("TravelOrderEmployees.EmployeeId='" . $id . "'")
                ->select(
                    'TravelOrders.*',
                    'TravelOrderEmployees.id AS TOEmployeeId',
                    DB::raw("(SELECT CONCAT(Day, ', ') 
                        FROM TravelOrderDays 
                        WHERE TravelOrderDays.TravelOrderId = TravelOrders.id
                        FOR XML PATH('')) AS Days")
                )
                ->orderByDesc('TravelOrders.DateFiled')
                ->get();

            $overtimes = Overtimes::where('EmployeeId', $id)->orderByDesc('created_at')->get();

            $offsets = OffsetApplications::where('EmployeeId', $id)->orderByDesc('created_at')->get();
            
            $payrollSundries = EmployeePayrollSundries::where('EmployeeId', $id)->first();

            $leaveBalanceExcess = LeaveExcessAbsences::where('EmployeeId', $id)
                ->orderByDesc('created_at')
                ->get();

            if (empty($employees)) {
                Flash::error('Employees not found');

                return redirect(route('employees.index'));
            }

            $userData = User::where('employee_id', $id)->permission('create leave for others')->first();

            $attendanceConfirmations = AttendaneConfirmations::where("EmployeeId", $id)->orderByDesc('created_at')->get();

            return view('employees.show', [
                'employees' => $employees, 
                'employeeDesignations' => $employeeDesignations,
                'rankings' => $rankings,
                'educationalAttainment' => $educationalAttainment,
                'ids' => $ids,
                'payslips' => $payslips,
                'workSchedules' => $workSchedules,
                'leaveBalance' => $leaveBalance,
                'leaveBalanceDetails' => $leaveBalanceDetails,
                'tripTickets' => $tripTickets,
                'overtimes' => $overtimes,
                'payrollSundries' => $payrollSundries,
                'travelOrders' => $travelOrders,
                'leaveBalanceExcess' => $leaveBalanceExcess,
                'userData' => $userData,
                'offsets' => $offsets,
                'attendanceConfirmations' => $attendanceConfirmations, 
            ]);

            // return response()->json([
            //     'employees' => $employees, 
            //     'employeeDesignations' => $employeeDesignations,
            //     'rankings' => $rankings,
            //     'educationalAttainment' => $educationalAttainment,
            //     'ids' => $ids,
            //     'payslips' => $payslips,
            //     'workSchedules' => $workSchedules,
            //     'leaveBalance' => $leaveBalance,
            //     'leaveBalanceDetails' => $leaveBalanceDetails,
            //     'tripTickets' => $tripTickets,
            //     'overtimes' => $overtimes,
            //     'payrollSundries' => $payrollSundries,
            //     'travelOrders' => $travelOrders,
            //     'leaveBalanceExcess' => $leaveBalanceExcess,
            //     'userData' => $userData,
            //     'offsets' => $offsets,
            //     'attendanceConfirmations' => $attendanceConfirmations, 
            // ]);
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    /**
     * Show the form for editing the specified Employees.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (Permission::hasDirectPermission(['god permission', 'update employee'])) {
            $employees = $this->employeesRepository->find($id);
            $towns = Towns::orderBy('Town')->pluck('Town', 'id');

            if (empty($employees)) {
                Flash::error('Employees not found');

                return redirect(route('employees.index'));
            }

            return view('employees.edit', ['employees' => $employees, 'towns' => $towns]);
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    /**
     * Update the specified Employees in storage.
     *
     * @param int $id
     * @param UpdateEmployeesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmployeesRequest $request)
    {
        if (Permission::hasDirectPermission(['god permission', 'update employee'])) {
            $employees = $this->employeesRepository->find($id);

            if (empty($employees)) {
                Flash::error('Employees not found');

                return redirect(route('employees.index'));
            }

            $employees = $this->employeesRepository->update($request->all(), $id);

            Flash::success('Employees updated successfully.');

            return redirect(route('employees.show', [$id]));
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    public function updateAjax($id, Request $request)
    {
        $employees = Employees::find($id);

        if ($employees != null) {
            $employees->ContactNumbers = $request['ContactNumbers'];
            $employees->EmailAddress = $request['EmailAddress'];
            $employees->save();
        }

        return response()->json($employees, 200);
    }

    /**
     * Remove the specified Employees from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (Permission::hasDirectPermission(['god permission', 'delete employee'])) {
            $employees = $this->employeesRepository->find($id);

            if (empty($employees)) {
                Flash::error('Employees not found');

                return redirect(route('employees.index'));
            }

            $this->employeesRepository->delete($id);

            Flash::success('Employees deleted successfully.');

            return redirect(route('employees.index'));
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    public function createDesignations($id) {
        if (Permission::hasDirectPermission(['god permission', 'create employee designation'])) {
            $employee = $this->employeesRepository->find($id);
            $departments = DB::table('Positions')
                ->select('Department')
                ->orderBy('Department')
                ->groupBy('Department')
                ->get();

            return view('/employees/create_designations', [
                'employee' => $employee,
                'departments' => $departments,
                'employeesDesignations' => null,
            ]);
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    public function getSearchResults(Request $request) {
        if ($request['search'] != null) {
            $param = $request['search'];
            $results = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Towns', 'Employees.TownCurrent', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Employees.BarangayCurrent', '=', 'Barangays.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->where('Employees.FirstName', 'LIKE', '%' . $param . '%')
                ->orWhere('Employees.MiddleName', 'LIKE', '%' . $param . '%')
                ->orWhere('Employees.LastName', 'LIKE', '%' . $param . '%')
                ->orWhere('Employees.id', 'LIKE', '%' . $param . '%')
                ->select('Employees.id', 
                    DB::raw("CONCAT(LastName, ', ', FirstName, ' ', MiddleName, ' ', Suffix) AS EmployeeName"),
                    'Employees.StreetCurrent',
                    'Employees.BarangayCurrent',
                    'Employees.TownCurrent',
                    'Employees.ProvinceCurrent',
                    'Employees.ContactNumbers',
                    'Positions.Position as Designation',
                    'Employees.EmailAddress',
                    'Towns.Town',
                    'Barangays.Barangays',
                    'Employees.ProfilePicture',   
                )
                ->orderBy('Employees.LastName')
                ->paginate(20);
        } else {
            $results = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Towns', 'Employees.TownCurrent', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Employees.BarangayCurrent', '=', 'Barangays.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->select('Employees.id', 
                    DB::raw("CONCAT(LastName, ', ', FirstName, ' ', MiddleName, ' ', Suffix) AS EmployeeName"),
                    'Employees.StreetCurrent',
                    'Employees.BarangayCurrent',
                    'Employees.TownCurrent',
                    'Employees.ProvinceCurrent',
                    'Employees.ContactNumbers',
                    'Positions.Position as Designation',
                    'Employees.EmailAddress',
                    'Towns.Town',
                    'Barangays.Barangays' ,
                    'Employees.ProfilePicture',  
                )
                ->orderByDesc('Employees.created_at')
                ->paginate(20);
        }

        return response()->json($results, 200);
    }

    public function updateRanking($id) {
        $employee = Employees::find($id);
        $rankings = DB::table('Rankings')
            ->leftJoin('RankingRepository', 'Rankings.RankingRepositoryId', '=', 'RankingRepository.id')
            ->where('Rankings.EmployeeId', $id)
            ->select('Rankings.id', 'RankingRepository.Type', 'RankingRepository.RankingName', 'Rankings.Points')
            ->get();
        $rankingsRep = DB::table('RankingRepository')
                    ->whereNotIn('id', function($q) use ($id){
                        $q->select('RankingRepositoryId')->from('Rankings')->where('EmployeeId', $id);
                    })->get();
        return view('/employees/update_ranking', [
            'employee' => $employee,
            'rankings' => $rankings,
            'rankingsRep' => $rankingsRep,
        ]);
    }

    public function addRanking(Request $request) {
        if ($request->ajax()) {
            $rankings = new Rankings;
            $rankings->EmployeeId = $request['EmployeeId'];
            $rankings->RankingRepositoryId = $request['RankingRepositoryId'];
            $rankings->Points = $request['Points'];
            
            if ($rankings->save()) {
                return response()->json(['response' => 'ok'], 200);
            } else {
                return response()->json(['response' => 'error saving ranking'], 400);
            }
        }
    }

    public function updateEducationalAttainment($id) {
        $employee = Employees::find($id);
        $educationalAttainment = EducationalAttainment::where('EmployeeId', $id)->get();

        return view('/employees/update_educational_attainment', [
            'employee' => $employee,
            'educationalAtainment' => $educationalAttainment,
        ]);
    }

    public function saveEducationalAttainment(Request $request) {
        if ($request->ajax()) {
            $educationalAttainment = new EducationalAttainment;
            $educationalAttainment->EmployeeId = $request['employeeid'];
            $educationalAttainment->Type = $request['type'];
            $educationalAttainment->Major = $request['major'];
            $educationalAttainment->School = $request['school'];
            $educationalAttainment->SchoolYear = $request['schoolyear'];

            if ($files = $request->file('file')) {
                $path = $request->file->storeAs('public/documents/' . $request['employeeid'] . '/' . $request['type'], $request->file->getClientOriginalName() . '.' . $request->file->extension());

                $educationalAttainment->Certification = $request->file->getClientOriginalName() . '.' . $request->file->extension();
            }

            if ($educationalAttainment->save()) {
                return response()->json(['res' => 'ok'], 200);
            } else {
                return response()->json(['res' => 'error'], 201);
            }
        }
    }

    public function downloadFile($folder, $type, $file) {
        return response()->file(public_path('storage\\documents\\' . $folder . '\\' . $type . '\\' . $file));
    }

    public function updateThirdPartyIDs($id) {
        $employee = Employees::find($id);
        $ids = ProfessionalIDs::where('EmployeeId', $id)->get();

        return view('/employees/update_third_party_ids', [
            'employee' => $employee,
            'ids' => $ids,
        ]);
    }

    public function attendance($id) {
        $employee = Employees::find($id);

        return view('employees.attendance', [
            'employee' => $employee,
        ]);
    }

    public function getAttendance(Request $request) {
        $month = date('Y-m', strtotime($request['month']));
        $endDate = date('t', strtotime($month));
        $employeeId = $request['employe_id'];
        $arr = [];

        // FETCH ALL LEAVE FROM THIS MONTH

        for ($i=1; $i<=intval($endDate); $i++) {
            $currentDate = date('Y-m-d', strtotime($month. '-' . $i));
            $attendance = DB::table('AttendanceRules')
                ->select(
                    DB::raw("(SELECT TOP 1 LogTime FROM Attendances WHERE EmployeeId = '" . $employeeId . "' AND (LogTime BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(LogTime as TIME) BETWEEN AttendanceRules.MorningTimeInStart AND AttendanceRules.MorningAbsentThreshold)) AS 'MorningIn'"),
                    DB::raw("(SELECT TOP 1 LogTime FROM Attendances WHERE EmployeeId = '" . $employeeId . "' AND (LogTime BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(LogTime as TIME) BETWEEN AttendanceRules.MorningUndertimeThreshold AND AttendanceRules.MorningTimeOutEnd)) AS 'MorningOut'"),
                    DB::raw("(SELECT TOP 1 LogTime FROM Attendances WHERE EmployeeId = '" . $employeeId . "' AND (LogTime BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(LogTime as TIME) BETWEEN AttendanceRules.AfternoonTimeInStart AND AttendanceRules.AfternoonAbsentThreshold)) AS 'AfternoonIn'"),
                    DB::raw("(SELECT TOP 1 LogTime FROM Attendances WHERE EmployeeId = '" . $employeeId . "' AND (LogTime BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(LogTime as TIME) BETWEEN AttendanceRules.AfternoonUndertimeThreshold AND AttendanceRules.AfternoonTimeOutEnd)) AS 'AfternoonOut'")
                )->first();
            
            $leaveAttDate = LeaveAttendanceDates::where('EmployeeId', $employeeId)
                    ->where('DateOfLeave', $currentDate)
                    ->first();

            array_push($arr, [
                'Date' => $currentDate,
                'MorningIn' => $attendance != null && $attendance->MorningIn != null ? date('h:i:s A', strtotime($attendance->MorningIn)) : '',
                'MorningOut' => $attendance != null && $attendance->MorningOut != null ? date('h:i:s A', strtotime($attendance->MorningOut)) : '',
                'AfternoonIn' => $attendance != null && $attendance->AfternoonIn != null ? date('h:i:s A', strtotime($attendance->AfternoonIn)) : '',
                'AfternoonOut' => $attendance != null && $attendance->AfternoonOut != null ? date('h:i:s A', strtotime($attendance->AfternoonOut)) : '',
                'HasLeave' => $leaveAttDate==null ? 'No' : 'Yes',
            ]);
        }

        return response()->json($arr, 200);       
    }

    public function captureImage($id) {
        $employee = Employees::find($id);

        return view('/employees/capture_image', [
            'employee' => $employee
        ]);
    }

    public function createImage(Request $request) {
        $images = EmployeeImages::where('EmployeeId', $request['EmployeeId'])->first();
        if ($images == null) {
            $images = new EmployeeImages;
            $images->id = IDGenerator::generateID();
            $images->EmployeeId = $request['EmployeeId'];
            $images->Image = $request['HexImage'];
            $images->save();
        } else {
            $images->HexImage = $request['HexImage'];
            $images->save();
        }            

        return json_encode(['response' => true]);
    }

    public function getImage($id) {
        $image = EmployeeImages::where('EmployeeId', $id)->first();
        if ($image != null) {
            return json_encode(['img' => $image->Image]);
        } else {
            $hex = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAYAAAD0eNT6AAAjr0lEQVR42u3deXhdZ33g8TgJ0LAFKE2gZAMSQizJjnyOZDdEukAs3XMk21cmucGx7vteJ1C1T0sKtOWhLVOcsk1KhxkyZRl2ytICLZQudIZSphtLEyA0KZRCN0iZhBAoZCHNas9zJJvGjhctV9I9534+z/P9p08JPKD395571mOOAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAYIWsG2s9ItkSTkvq0xuG8pCm9bC5KMnCRJKF5r4m9v/fZ/9/sjBY/GuKf63/BgGgywxM7nxssWEnWbworYeXJHl8Y5rHj6V5uC7JwjeTPPxHmse9S6n4ZxT/rOKfWfyzkyy+Ic3iLxb/nsW/9/pG+zH+lwCA5bB797HD2fTaNGvtSPP4X9M8/HGShxuWurl3rCx+Y/Y/UxZeU/xnHJwI5xxzzDFr/A8HAAuQbJl5eJKF89M8vjDN44fSLN7SNZv9fM8cZOG2JI+fSPJwxewlhy0zD/e/LAAcJJ289CnFhl9smmke7irbhn/0Swnx3jQLn0qy+NINk+3EGQIAevNXfjLzkKGJdpZk8a1JHv+tahv+PO4tuCHJw1uSLI7XarXj/UUAUF27dx87d2o/XJVk8Vu9tukf4WDgu0kW35Nmra3FgZE/FAAqIa1Pn53k4TfSPN5swz/qvQM3pVn49Y2Tu87ylwNA6ZyZXf6w2WfsZ6/pxz0298U8XRA+n2btGTcQAtD1Nm7dcXKahVcmefyOTbxjjxnekmbxFevGWif5CwOguzb+yV1n7bu2f6dNe9kOBO4u7hUYqsc+f3EArKqhifa5SR7+wGn+Fb00cH+Sh48MZnGdv0AAVlTxtru5O9fD/TblVWtPksU/Gh5vrfcXCcCy2lhvn5Hk4X02/m56ciDeVxyMFR8w8hcKQEfNvp43D1e4xt/VBwJ3pnm48rxtlz3KXywAS7WmeJxv7oM3NtlyFP5f8fhg8eIlf74ALFjxBb4ki5+2oZa2v07G20/3lwzAvBSvoy0+WlPFj/L04NmAe4rLAsWLmfxlA3BYQ/XwE0kevmTjrNyjg9cnE62N/sIBOEDxRbp9N/ndZ8Os7tMCxdkAHxwCYFbxaF/xrXqbZM9cFrh6cLx1pr98gB6WZK3npXm83abYc18dvC3JQtsKAOgxtVr7R9I8vt1m2POXBd6zqdk8wYoA6AHp+K5TkzxcYwPU7EFAHq8dHms92coAqLBkIj4rzePNNj4ddBDwnSSL41YIQBV/+WftmSSP99rwdNhvCuTxBVYKQHWsKR7xs8lpnk8JXOU1wgAlV3zEJ83iR21qWtglgfCR4m/HCgIooaELwo8Wz3zb0LTI+wI+MzC587FWEkCJbNy64+Q0D9fZyLTEg4AvJ/XpJ1pRACVQDGzv81cHDwL+YTibPsXKAuhi6eSlT0ny+C82LnX4zYH/7F0BAF2q+JVm89cy3hh4w+DEztOtNIAusm6sdVKah6/YqLTMZwK+5p4AgC6RbLnk8a75a8XKwvXFEyZWHsAqGs6mH53m8Qs2Jq3w5YBr1taaj7QCAVbjl38y85A0Dx+3IWmVLgf8Sa1WO95KBFhhaR7eZCPSKl8OeJuVCLCim3/8FRuQuuMgIP6iFQmwApIsNNM87rH5qEvOAtw/lMXtVibAcm7+4+2nJ3m81cajLuv24Wx6rRUKsAzO23bZo9Is/r3NRt36yuBk88yJVipAZ61J8vhhG426/H6AjxZ/q5YrQIekefxlG4xK8o6AX7BiATpgw2Q7SfNwj81F5Sjck4zHYSsXYAnWjbUekWTxqzYVlax/9KZAgCVI8vgOm4lKej/Am61ggEVI6+E5NhKVunrcZiUDLMCm8ec9Lsnit2wiKvn3Am4amNz5WCsaYP6n/t9lA1FFngp4ixUNMJ/NfyI+y6t+VaH2DGXtC6xsgCNt/ltmHp7m4Z9sGqrYpYCvbWo2T7DCAQ4jzeOrbBiq6FMBu61wgENt/uO7Tk2z8AObhap5FiDeOTix83QrHeDBv/4/ZKNQxc8CvN9KB3jg5j/ROs+Nf+qFGwKTLJxvxQPMWZNm4fM2B/XIWYC/8cVAgNlT/+0LbQzyhkCAXrJ797FpHq6zKai3zgKE64u/fQMA6FlJPV5iQ1CPPhVwkQkA9KRms3lcmoev2AzUm68Ijl92FgDoSWnW2mEjkLMAAL12AJCHq20C6vEPBV1jEgA9Jam3n2kDkGa/E+C9AEAPHQBk8Y8Mf2n2vQC/byIAvbH5j7efnmbhfsNfmn0k8P6hPDzNZAB64Nd/eL3BLx1wFuB1JgNQaWdmlz8szeIthr50wCOB3ynWhgkBVNZQ1p428KUHNzQRLzYhgOqe/s/jXxj20iHPAnzChAAqaePkrrN88lc6/KeCh8daTzYpgMpJs/ByQ146Yr9sUgAVPP0fvmTAS0e8DPC3JgVQrV//k61+A146eoMT4RwTA6jQ6f/4CsNdmteLgV5uYgBVOgD4e8NdmtcXAv/OxAAqobiz2WCXFnIZYOfpJgdQ/l//eetnDXVpAWcB6vGnTA6g9Hz5T/KFQKDHzL77P4+3G+rSQu4DCLetbTYfaoIA5T39Xw+bDXRpMZcB2s80QYDyHgBk4ZWGueRxQKD3DgD+r2EuLabwcRMEKKVarXa86//Sol8LfGuz2TzOJAFKZ8NkOzHIpcU3NNE+1yQBSifJwuWGuLSUWj9rkgClk+bx3Qa4tKTLAO8wSYDynQHI47WGuLSk7wJ8ziQBSqW4ATDJw38Y4tKSDgDudCMgUCpD9dhngEsdqD59tokClEaatXYY3lJHzgJcZKIAJToAiLsNb6kjXwb8LyYKUJ4DAE8ASJ3q7SYKUBpJHv/C4JY68mngT5ooQJkuAXzD8JY68mngfzZRgHL8+k9mHpJk8T7DW+rIy4DuLR6rNVmArjc4sfN0g1vqYOO7TjVZgBKc/t81ZGhLnXwSYHqDyQJ0/wFAHnNDW+rouwDGTRagBGcAYsvQljr6LoBLTBagBAcA4UWGttTRJwEuN1mAMpwBeIWhLXXySYBwhckClOEA4HWGttTRRwFfa7IAZbgE8JuGttTJwlUmC9D1kjy8xcCWOvo64DebLEAJDgDiuwxtqaM3Ab7TZAFKcAkgvt/Qljp6E+D7TBag+88AZOGDhrbU0RcBfcBkAUpwCSC819CWOnoA8B6TBSjBAUB8h6EtdbS3myxA1yvuWDawpY6+B+CNJgvQ/QcAebjK0JY6+h6A/26yAGW4BPBaA1vq6AHAlSYL0P0HAFl4mYEtdfQ9AL9ksgAlOAMQftrQljrXUB5+0mQBSnAGIF5kaEsdPADI4naTBej+A4CJ+CxDW+rgAcBEHDVZgBJcAtg1YGhLnWs4m15rsgBdb91Y6yRDW+rgTYBbLnm8yQKUQprHOwxuqSNPANxmogAlugwQv2x4Sx0oC9ebKECZzgB8zPCWOvIp4D8wUYDynAHI4hsMb6kjbwG8ykQBSnQJIPyCwS115BLAi0wUoDQ21EPd8JY68RKg9gUmClCeMwD16Sca3tLSKx6rNVGAUknz8G0DXFrSI4A3mSRA+Q4AsvhJQ1xayvX/+KcmCVC+ywB5/B+GuLSkA4DXmSRA+Q4A6vESQ1xa0keALjZJgPIdAGwJpxni0pI+AnSKSQKU9DJAuMEglxb1BsB/NUGA8h4AZPEDhrm0qAOA95ogQIkPAMLlhrm0qAOAnzZBgNIaqsc+w1xaRPXps00QoNTSLH7DQJcW8us//ovJAVTgMkB8q6EuLegA4I0mB1D+ywBZ3G6oSwt5AVBrq8kBlN552y57VJqHewx2aV5v/7t7ba35SJMDqIQ0Dx833KV5fQDoT0wMoDoHAFm4zHCX5nX9P5oYQGWsb7QfU5zaNOClIxXuSjbPnGhiABW7DBA/ZsBLR7z+/1GTAqicJAttQ1464tv/dpoUQOUUdzYnWbjNoJcO2e3u/geqfBng7Qa9dMjr///LhACqexlgorXRoJce3IbJdmJCANU+CMjCFw186YBf/9eZDEAvHAD4RLB0wN3/4WdMBqDyZl8NnIXvGfzS7KN//+7mP6B3zgLk8bWGvzT76t/XmAhAz9g0GZ/kzYBSuGc4mz7FRAB67CxAeK8NQD3+6/+dJgHQc4bHW+vTPO6xEahHb/y7P51s9ZsEQK+eBfiIzUA9+uv/gyYA0LOG6rFv9peQDUE9tfnH+wYnwjkmANDbZwGy+AGbgnrsoz/vtfIBZwHy8LQkj/faGNQrv/7T+vTZVj5A8ZGgLLzN5qAeefHPm614gH3WjbVOSvPwfRuEKn7n//eSLZc83ooHeOBZgHp4iU1CFT8AeJGVDnCQtc3mQ5MsftVGoYq+9e8rSTLzECsd4JBnAeI2G4UqWm6FAxxBkscP2yxUrcf+4u9Y2QBHMTTRfkLxiVQbhyryzP93N27dcbKVDTCfSwFZe8bmoYp0qRUNMH9r0jz8uc1DJf/1/2fF37LlDLAAw2OtJyd5vNVGopLe9f/9jfX2GVYywCIkeYw2EpX01/9OKxhgCdI8/LYNRSV74c9vWbkASz0LsHnmxDSLX7exqCSP/P3LcDb9aCsXoBNnASZa56VZvNsGoy6/7n9XMh6HrViAjl4KaP2sDUZd/uv/+VYqwHJcDsjCO2006srNP4vvsUIBlkmt1v6RNAuft+Gouzb/8MVNzeYJVijAMiqerU6y+C0bj7qkG9PxXadamQArYMNkO0nzeIfNR6v8uN8P3PQHsMKSensyyeJ9NiKt0jX/+5KJ0LASAVbjICCLL7YZaZWu+19uBQKsojQPV9qQtMKn/l9p5QF0w0FAFl9nY9IK9T+tOIDusSbJ4lttTlrm3u3zvgBdptlsHpdk8QM2KS3Ta35/u/gbs9IAuvYgwNsC1fFP+76vVqsdb4UBdLc1aR6usnGpMzf8xTcfs3v3sZYVQEkOApI8/IYNTEs87X+lpQRQQmkefyXN4x4bmRbYnrQeXmIFAZRYksWLkizeaVPTPH/135XU4yVWDkAFDNXDT6R5+LbNTUe52e+7G+rTI1YMQJUOAvLwtCQLX7PR6TC//L8yON4600oBqKDztl32qCSPH7bZ6cBf/vEP1zfaj7FCAKqteGvgS31JULNf9MvDFR7zA+ghQxPtLMnjd2yEPXvK/9tpPWy2EgB60MatO05OsvAnNsOeO+X/icHNl/24FQDQ29akeXxh8fiXzbEHHvHL4kud8gfgh9IsDCZ5/PIK/xK1Ka/c9f6/Gx5vrfeXDsCDJMnMQ4pfiM4GVOpX/z3FK33PzC5/mL9wAI58NmCy1Z/m4bM2z9L/6v/0UD32+YsGYP527z42ycLlaRa+ZzMt3Rf8/j3Nws+41g/Aom0af97jis8LJ3m81+ba7Rt/uD/J4nvWjbVO8pcLwKIlWy55fJKFZrGpJFm4zSbb9Y/33Zlk8Y/SrD3jMT8AFmTDZDtJs7g7ycM1xS9KG2t5zwakebg6zcLLk/r0Bn/ZADzI4EQ4p3glbJLFr9o8K3tfwNeLpwGS8fbT/cUD9LDhbPqU2RcAZeFTNsieu1Tw5X0HfE+1EgB6wMDkzscmeYzFq2DTPO6xGSrNwueLA8HitdBWCEDFJFk4P83jh+ZeCmPT02G+DFjcQDj3gaA1Vg1AWTf9zTMnFneDF6+AtcFpYQcD4Wuzn4zecsnjrSSAkiju4k/y8JY0Cz+wmWmpHw+aPXPks8EA3WlTs3nC7K/9PF5r09IynRX4YvE3VvytWXEAq2zuRT3Fx3vijTYprdDjhLfMPk5Yn36iFQiwwtLJS59SvJ7XaX6t5uWB2TdEeq8AwAr84s93Dcxek/WGPnXV0wPhg8UXJK1QgI7/4m/1z72PP95n01GXtqd4jNBrhwE6uPH7xa+yHQgUT6NYwQALtHFy11mzp1W9rU/lPhD4wOB460wrGuAoirv6izus9z1/bRNRFW4WvKd4L4WnBgAOYW2t+cjZN6/l8VYbhiraHbOPD26eOdGKB9i9+9gkj89P83izDUI98tTAt9IsXFb87RsAQE9Ks11DaR4+a1NQj/aF4iNVJgHQMwY3X/bj+97V785+9fyNgsV7LZIt4TSTAaisWq12fJKHn0/zeLvBLx3Q7UkWX9xsNo8zKYCKne4Pg0kerjHopSPcH5DHv00mWhtNDKD01o21HjF757M3+Enz/NhQuL+4RDacTT/aBAFKKZkIjSQL3zTUpcWcDQg3bMjDFpMEKM/Gv3nmxNmb/AxxqRN9aNP48x5nsgBdbUM91JM8/puhLXXy3QHhpjRrbTVhgK5TXK/c96vfu/ulZTwbMDC587EmDtAdp/wnWhvTPPyT4SytxE2C8etJ3n6GyQOsmuKZ5eL9/cXHTgxmaUUfF7w3ycMV3hsArLh0fNepSRb+0jCWVvVLg38+nE2fYiIBK7P55+0L0yx8z/CVuuJxwe8Wj9yaTMCyKV7lW7zUx41+Ujd+UyBclSQzDzGpgI5aN9Y6Kc3iJw1aqav7q6Q+/UQTC+iIoYk4mubxRsNVKkU3D2XtC0wuYEmSPPxCccexoSqV7CmBLL7YBAMW7Mzs8ocleXyXYSqV+p0B79/UbJ5gogHzcm526Y95xE+qzNmAzwxNtJ9gsgFHNDTRPrd405jBKVXqWwLfHMpDasIBh5Rk8aIki3camFIluyPJW1MmHXCANI8vTLNwvyEpVflMQLwvyeMLTDygsGbfy30MSKl3XiF81TG7dx9r/EGP2nen/+8YhlJP3hfwe54QgB5UfFM8zcKnDEKpp58Q+Itk88yJJiL0iI1bd5ycZOGLBqCkNI9fKB79NRmh4pIt4bQki1819CQ94J6ArxSf+DYhoaLS+vTZSR5uMOwkHeKtgV/fOLnrLJMSqrb5Z2Gw+EiIQSfpCDcG3pROtvpNTKiI4u1+SR6/Y8BJmsflgG8PZnGdyQk2f0k9eBCQ5LsGTFAoqeHx1nqbvyQHAdBjm3+axVsMMUkOAqBXNv9seq3NX1KHurl4gshkhS5XPOefZvEbhpakTn5OeGO9fYYJC11q3VjrpCSP/2BgSVqG/nFoov0Ekxa6zPpG+zFe7ytpeV8WFK7fNP68x5m40CXW1pqPTPNwtQElafkvB8RPJ1tmHm7ywiprNpvHpVn8qMEkaQWfDvjjWq12vAkMqyjJ4hsMI0kr/ynh8BYTGFZt8w8vM4gkrVr18BKTGFZYmrV2pHncYwhJWsX2pFlsmciwYpt/qKVZvNvwkdQF9wPcleTtZ5jMsMwGJ3ae7rO+krrr8cB4y/BY68kmNCyTfY/7XWfgSOrCtwV+cd1Y6xEmNXTemjSPHzJoJHXvkwHxw8WsMq6hg5IsvNqAkVSCywG7TWzokDRvX+iOf0mleTKgHreZ3LBEg+OtM9M8fN9QkVSibwZ8L8niU01wWKRarf0jSR6vNVAklfDxwOt8MwAWe90/j+8yRCSV+HXB7zXJYaHX/bP2jAEiqQKXAy4z0WHev/x3DSR5+A/DQ1L53w8Q7xyqxz6THeZx3d/LfiRV7FLAlzY1myeY8HCkU/95eJOBIamCNwVeZcLD4U79Z2HC8/6SKvt+gKy11aSHg2yajE8qPqhhSEiq8FmAbyf16Sea+PCf1qRZ/FPDQVIP9DEjH/af+s/DTxsKkjwaCD1kY719RpKF2wwFSb3zVEC8NdkSTrMD0Nun/vPwcQNBUg8+GvhnPh1MD5/6jy8wCCT17qWA9oydgJ4zOLHz9DSPdxgCknr3LYHhtuFs+hQ7Ar326/8PDQBJCn9sR6BnDE3Eiy16Sdp/P0Brys5A5Q1n049OsvBNi16SftiNyeaZE+0QVJp3/UvSIe8HeL0dgsrakLU3pVm432KXpIMPAOJ9Q3lI7RRUz+7dx6Z5uNpCl6TDviDoM94NQPVO/WfhMgtcko72boDYsmNQGedtu+xRxU0uFrckHfVegJuKm6XtHFRCkoffsLAlad4HAa+xc1D+zT+LT03zcJdFLUnzvgxw98bJXWfZQSj7r/+PWNCStOA+ZAehvJv/RGtjmsc9FrIkLbg9Q/XwE3YSSinNw59bxJK06HsB/tJOQvk2/3rcZgFL0pLL7SiURrPZPC7Jw5csXEla6g2B4friRWp2FkphqN7aZeFKUmcaytrTdhbK8es/C1+zaCWpY/cCfK1Wqx1vh8Gvf0lyFgC67dd//KrFKkmdPwtQzFg7DV0pyULbQpWkZToIyMNOOw1deud//AeLVJKcBaCXfv3nYacFKknLfC/ARLzYjkOXHQDEay1OSVruswDxc3Ycumjzb41ZmJK0QgcB9fYz7Tx0hTSLf2pRStKK9TE7D6tuMIvrfPFPkla0PcPjrfV2IFb75r/3WYyStOK92w7EqhmaaD8hzeLdFqIkrXBZvHvj1h0n24lYpWv/4eUWoiStWr9iJ2LFFR+mSPL4bxagJK3amwFv8JEgVv7Xf96+0AKUpNU+CGhN2ZFY4dP/8ZMWnySt9gFA/IQdiZW783+8/XSP/klSdzwSmNanz7YzsTIHAHl8rUUnSd1SuNLOxIrc/Jfm8UYLTpK65DJAFr/lZkCW/9p/PW6z4CSpyw4C6u1JOxTLffPf71tsktRtZwHC79mhWDbrxlonpXm4x2KTpO57M+C52aU/ZqdieW7+y+KLLTRJ6trLAD9np2K5DgA+Z5FJUtc+DfBZOxWdv/Y/eelTPPsvSd39ToCN9fYZdiw6/Os/vMzikqSufyTwpXYsOnz3f7je4pKkrn818LV2LDr363/u1b8WlySVoMGJcI6di84cAOThCotKkspyFiD8qp2Lzpz+z8N1FpUklaYv2LlYssGJnadbTJJUrqcB0vFdp9rBWNrp/3r75ywmSSrbmwHDz9jBWOr1/z+zmCSpdP0fOxiL3/w3z5xYvF/aQpKksp0BiHcPZ9OPtpOx2Jv/nmshSVJpXwp0kZ2MxZ0ByMI7LSJJKu19AG+zk7HY6/83WESSVNrLAF+3k7Hwzd/b/ySp9G2c3HWWHY2Fnv6/3OKRJI8D0nun///A4pGk0l8G+H07GvNWq9WOT/J4q8UjSWUvfL+Y6XY25nn9Pw5bNJJUjYbykNrZmO/p/5+3aCSpMvcBvMjOxjwPAOKHLRpJqsxlgN+1szHfJwBusmAkqTJvBPyWnY2jGsrD0ywYSarcQcBT7XAc5dd/63kWiyRV7QAgtO1wHOUAIL7VYpGkyr0P4M12OI52A+C1FoskVewMQB6uscNxWGubzYcW35C2WCSpck8C3FXMeDsdh7sBMLVIJKmy7wMYtNNx6NP/9fhTFokkVfUyQHy+nY7DXP8Pb7FIJMmNgPSYNA9XWySSVNkDgL+x03Eoa9I83m6RSFJlu72Y9bY7DrCx3j7D4pCkit8HsCWcZsfjwOv/WZiwOCSp2g1NtDM7Hgde/8/iL1ocklT5FwL9vB2Pg54AiO+wOCSp8u8CeJsdj4MPAD5jcUhSxc8AZPHTdjwOvgRwi8UhSZXvZjseP3TetsseZVFIUm+0ttZ8pJ2Pfaf/dw1YFJLUI022+u18zJ3+r8dtFoUk9Uj1uM3Ox9wBQB5faFFIUs/0Qjsfc5cAsvB6C0KSeuVJgPB6Ox/7zgCE37YoJKlnHgX8gJ2PfTcBegmQJPXO2wDjO+x87D8DcJVFIUm9UrjKzsf+ewBebUFIUs/0Kjsf+w8AfsmCkKSeuQnwl+x87LsHIOy0KCSpRw4A6vESOx9z9wBku4YsCknqjYbykNr5mLW+0X6MRSFJvdHA5M7H2vl4wFkAXwOUpMqXxVvseBz8KODHLQ5JqvwNgP/bjscBNtTDlRaHJFX+AODVdjwOcO7m5/ogkCRVvPXPfu4L7HgcoH90+88lFockVffXfx739p+//XI7HgfoG2385obxnRaJJFW0wbGde/tGGl4DzMFnABqfXP/siy0SSars6f/m3r6RqU/Y8TjoAGDqhoHa9uIREQtFkip4+r+Y8f2jU9+w4/FDa9c2H9o/2ri/f3Rq76DLAJJUvdP/4zuLzb+4BHBfMfPtfMwdANS2nln8YRSte9ZFFoskVax1z7xo7/453/esqafa+dj/BMDY/j+Mog31aQtGkqpy+r8+vfeBM36gtm2znY99ZwCmfvKBfxzFjSIWjSRV5Nf/s5sHHAAUM9/Ox9wZgJGpVz/wj6MoqbcsHEkq/Zv/WnsPnu99o43X2PnYfwDwnoP/QJwFkKRqPPp3iAOA99n52H8A8FsH/4G4F0CSyt2Gg679/7CRqd+y87H/AOCNh/ojGXjmc7wXQJLKWFbc+X/h4Q4A3mjnY/8BwK8f8o9kdGrvuWM7LCRJKttz/5t37D3cXO8bmbrSzse+xwCnXn64P5SB2RsCXQqQpPKc+m/t7a9NHfYAoH+k8at2Pg75GOChLgX4UqAklaFw+FP/PzwD0Hi+nY9ZfbXGM4/0xzL3VIAPBUlS19/1f8HFe482z/vPn6rZ+Zg1UNt+ylH/YIrvBGx2P4Akde11/7Ede+czy9c/e/JJdj72W9M3MnXHfP5wNoz5WJAkdd11/30f+zlafaNTPyhmvm2PBzwJ0LhmPn88/aPbvR9Akrruef/t8zoAKGa9HY8D7wMYabxufgcA+w4Cxh0ESNLq//Kf3jtQm+fmP/cI4H+z43HgGYDzG1PzPwCYOwgYHL/EApSk1TztX1vI3J7aOzAy1bDjcYCn1bY8vn90as/CDgKKGwMdBEhSt97wd1B7nn7B1I/a8TjEfQBT1y7iD2rfh4OCRSlJy1xymA/8zPMGwM/b6Tj044CjjZcu5o+qaF3xsiCfEJak5dv8s9begaO85Oco1/9fYqfjkNY+Y8tpi7kMcMDZgAue62yAJHX6lP/m586+mn0J83nP+lrjDDsdR3oa4DP9S/sjmz0bUNycYtFK0lIf8du5d6D2nL1Lncv9o1OftsNxlMsAUzMd+EPbdyBwoQMBSVrk433rn3XR3k7N42K22+E4ojOz7GH9I42bOvVHN3cgcNHcewMyi1qSDls2t/EXM7OTM7hvtHHzpk3NE+xwHP1pgCN8HnhJR6C15+w994KLfV5Ykh54c1+9tffc4hp/Z071H+ru/5fZ2ZiX4p0Axfuil+MP8T8/MXzh7A2Dg2M79yaZmwYl9dLd/GF29hU/iJZyV/887/y/w7P/LOwswEjjVcv5R3mo+wWKZ1uLBVG8YbA4S7Ch3pp7tNABgqRSncoPs7NrboZNz8609ZufOzvj1i3zhv/gA4DGr9nRWJAk2fLw/tGpG1byD1WS1NFf/99cNzb2CDsaC38kcHRbtIgkqaSNbJ+2k7FYa/pHG5+ykCSpdL/+/6qY4bYxFm3dyNYn949O3WpBSVJZfvk3bl9b23qmHYwlWzsy1baoJKkknT/VsnPRuacCRhu/Y2FJUtef+n+/HYuOKt4i1TfS+KwFJkldu/l/zl3/LItzz2/+WP/I1D9aaJLUdZv/v/Y/e+vJdiqW736A87et7R9tfNeCk6RuqfHdvtGpc+xQLLtznrG1r3+0caNFJ0mr/Mt/tHHz2lrjXDsTK2bgvG1ne1OgJK3qL/8bix9kdiRWXPGOgL6RxlcsQkla8Wf9/359rXGGnYjVuyeg1nxk30jjgxakJK3Yaf+PJpubJ9qB6I5LAqNTM32jjXssTklatjv97x0YbbzUK37pOn3P2L6+f7RxtYUqSZ0+5T/1xb7R7UN2GrrY7mOLswH9o1O3WbSStNTT/VM/KH71N5vN4+wvlOOSQG37Kf0jjTf1j0zdZRFL0oJ/8d/VPzL1xvXPnnySHYVyXhYYaZzaP9p4gwMBSZrnxj/aeEPxI8oOQiWsrzUeM3dpoPEpi1ySHtSXi1P9xSvX7RhU9/LAaGOgb6Txa8XBQHFXq4UvqQcf5bunf6Tx130jjSvW1bb32xnoOcV7BPpHtk/2jzRe1T869bt9I1PXu1wgqWqn9ftGp64rZlz/aOOVxcwrZp8dAA5S3O1a3DtQvOLynPO3bRyobdu89vxtFw3UtjclqZubm1XbNhezq5hhxSwrno4y2QEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA4Gj+P3Tbnzm7DD0pAAAAAElFTkSuQmCC';
            return json_encode(['img' => $hex]);
        }
    }

    public function getEmployeeAjax(Request $request) {
        $id = $request['id'];

        $employee = Employees::find($id);

        if ($employee != null) {
            return response()->json($employee, 200);
        } else {
            return response()->json('Employee not found!', 404);
        }
    }

    public function getEmployeesAjax(Request $request) {
        $employees = Employees::orderBy('LastName')->get();

        if ($employees != null) {
            return response()->json($employees, 200);
        } else {
            return response()->json('Employee not found!', 404);
        }
    }

    public function getAttendanceDataAjax(Request $request) {
        $employeeId = $request['EmployeeId'];

        $employee = Employees::find($employeeId);

        $data = [];

        if ($employee != null && $employee->BiometricsUserId != null) {
            $attendanceData = AttendanceData::where('BiometricUserId', $employee->BiometricsUserId)
                ->whereNull('AbsentPermission')
                ->orderBy('Timestamp')
                ->get();

            $leaveDays = DB::table('LeaveDays')
                ->leftJoin('LeaveApplications', 'LeaveDays.LeaveId', '=', 'LeaveApplications.id')
                ->whereRaw("LeaveApplications.EmployeeId='" . $employeeId . "' AND LeaveApplications.Status='APPROVED'")
                ->select(
                    'LeaveDays.LeaveDate',
                    'LeaveDays.Duration'
                )
                ->groupBy('LeaveDays.LeaveDate', 'LeaveDays.Duration')
                ->orderBy('LeaveDays.LeaveDate')
                ->get();

            $tripTickets = DB::table('TripTicketPassengers')
                ->leftJoin('TripTickets', 'TripTicketPassengers.TripTicketId', '=', 'TripTickets.id')
                ->whereRaw("TripTickets.Status IN ('APPROVED', 'DEPARTED', 'ARRIVED') AND TripTicketPassengers.EmployeeId='" . $employeeId . "'")
                ->select(
                    'TripTickets.DateOfTravel',
                )
                ->orderBy('TripTickets.DateOfTravel')
                ->get();

            $offsets = OffsetApplications::where('EmployeeId', $employeeId)
                    ->where('Status', 'APPROVED')
                    ->select('DateOfOffset')
                    ->get();

            $dayOffs = EmployeeDayOffs::where('EmployeeId', $employeeId)
                    ->select('DayOff')
                    ->get();

                    // TRAVEL ORDERS
            $travelOrders = DB::table('TravelOrderDays')
                ->leftJoin('TravelOrders', 'TravelOrderDays.TravelOrderId', '=', 'TravelOrders.id')
                ->leftJoin('TravelOrderEmployees', 'TravelOrderEmployees.TravelOrderId', '=', 'TravelOrders.id')
                ->whereRaw("TravelOrderEmployees.EmployeeId='" . $employeeId . "' AND TravelOrders.Status='APPROVED'")
                ->select(
                    'TravelOrderDays.Day'
                )
                ->orderByDesc('TravelOrders.DateFiled')
                ->get();

            $data['Biometrics'] = $attendanceData;
            $data['Leave'] = $leaveDays;
            $data['TripTickets'] = $tripTickets;
            $data['Offsets'] = $offsets;
            $data['DayOffs'] = $dayOffs;
            $data['TravelOrders'] = $travelOrders;

            return response()->json($data, 200);
        } else {
            return response()->json([], 200);
        }
    }

    public function allowNoAttendance(Request $request) {
        $id = $request['id'];
        $status = $request['Status'];

        if (Permission::hasDirectPermission(['god permission', 'set allow no attendance'])) {
            $employee = Employees::find($id);
            if ($employee != null) {
                $employee->NoAttendanceAllowed = $status;
                $employee->save();
            }

            return response()->json($employee, 200);
        } else {
            return response()->json('You are not allowed to access this module', 403);
        }
    }

    public function savePayrollSundries(Request $request) {
        $employeeId = $request['EmployeeId'];
        $longevity = $request['Longevity'];
        $riceAllowance = $request['RiceAllowance'];
        $insurance = $request['Insurance'];
        $pagIbig = $request['PagIbigContribution'];
        $sss = $request['SSSContribution'];
        $philHealth = $request['PhilHealth'];
        $notes = $request['Notes'];

        if (Permission::hasDirectPermission(['god permission', 'update payroll sundries'])) {
            $sundries = EmployeePayrollSundries::where('EmployeeId', $employeeId)->first();
            $employee = Employees::find($employeeId);

            if ($sundries != null) {
                $sundries->Longevity = $longevity;
                $sundries->RiceAllowance = $riceAllowance;
                $sundries->Insurances = $insurance;
                $sundries->PagIbigContribution = $pagIbig;
                $sundries->SSSContribution = $sss;
                $sundries->PhilHealth = $philHealth;
                $sundries->Notes = $notes;
                $sundries->save();
            } else {
                $sundries = new EmployeePayrollSundries;
                $sundries->id = IDGenerator::generateIDandRandString();
                $sundries->EmployeeId = $employeeId;
                $sundries->Longevity = $longevity;
                $sundries->RiceAllowance = $riceAllowance;
                $sundries->Insurances = $insurance;
                $sundries->PagIbigContribution = $pagIbig;
                $sundries->SSSContribution = $sss;
                $sundries->PhilHealth = $philHealth;
                $sundries->Notes = $notes;
                $sundries->save();
            }

            if ($employee != null) {
                $employee->Longevity = $longevity;
                $employee->save();
            }

            return response()->json($sundries, 200);
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    public function updateOffice(Request $request) {
        $office = $request['Office'];
        $id = $request['id'];

        Employees::where('id', $id)
            ->update(['OfficeDesignation' => $office]);

        return response()->json('ok', 200);
    }

    public function updateDateHired(Request $request) {
        $dateHired = $request['DateHired'];
        $id = $request['id'];

        Employees::where('id', $id)
            ->update(['DateHired' => $dateHired]);

        return response()->json('ok', 200);
    }

    public function updateEnd(Request $request) {
        $dateEnded = $request['DateEnded'];
        $type = $request['Type'];
        $id = $request['id'];

        if (Permission::hasDirectPermission(['god permission', 'update retirement'])) {
            Employees::where('id', $id)
                ->update(['DateEnded' => $dateEnded, 'EmploymentStatus' => $type]);

            return response()->json('ok', 200);
        } else {
            return response()->json('You are not allowed to access this module', 403);
        }
    }

    public function updateBiometricsId(Request $request) {
        $biometricsId = $request['BiometricsId'];
        $id = $request['id'];

        Employees::where('id', $id)
            ->update(['BiometricsUserId' => $biometricsId]);

        return response()->json('ok', 200);
    }

    public function updatePitakard(Request $request) {
        $pitakardNo = $request['PitakardNo'];
        $id = $request['id'];

        Employees::where('id', $id)
            ->update(['PrimaryBankNumber' => $pitakardNo]);

        return response()->json('ok', 200);
    }

    public function uploadFile($employeeId) {
        if (Permission::hasDirectPermission(['god permission', 'upload employee files'])) {
            $employees = DB::table('Employees')
                    ->select('Employees.FirstName',
                            'Employees.MiddleName',
                            'Employees.LastName',
                            'Employees.Suffix',
                            'Employees.id',
                    )
                    ->where('Employees.id', $employeeId)
                    ->first();

            return view('/employees/upload_file', [
                'employeeId' => $employeeId,
                'employee' => $employees,
            ]); 
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    public function saveUploadedFiles(Request $request) {
        $id = $request['EmployeeId'];
        $files = $_FILES['files'];

        $path = Employees::filePath() . "$id/";
        File::makeDirectory($path, $mode = 0777, true, true);

        foreach ($_FILES["files"]["name"] as $key => $fileName) {
            $tempFileName = $_FILES["files"]["tmp_name"][$key];
            $targetFileName = $path . basename($fileName);
    
            // Move the uploaded file to the target directory
            if (move_uploaded_file($tempFileName, $targetFileName)) {
                echo "The file " . basename($fileName) . " has been uploaded.<br>";
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }

        return redirect(route('employees.show', [$id]));
    }

    public function fetchFiles(Request $request) {
        $id = $request['EmployeeId'];

        // FILES
        $path = Employees::filePath() . "$id/";
        if (file_exists($path) && is_dir($path)) {
            $fileNames = scandir($path);
            $fileNames = array_diff($fileNames, array('.', '..'));
            sort($fileNames);
        } else {
            $fileNames = [];
        }

        $files = [];
        foreach($fileNames as $file) {
            if (in_array($file, ['trash'])) {

            } else {
                $lastModified = filemtime($path . '/' . $file);
                $formattedDate = date('Y/m/d h:i A', $lastModified);

                array_push($files, [
                    'file' => $file,
                    'dateModified' => $formattedDate
                ]);
            }
        }

        return response()->json($files, 200);
    }

    public function renameFile(Request $request) {
        $id = $request['EmployeeId'];
        $oldFileName = $request['OldFileName'];
        $newFileName = $request['NewFileName'];

        // FILES
        $oldFile = Employees::filePath() . "$id/" . $oldFileName;
        $extension = strtolower(pathinfo($oldFile, PATHINFO_EXTENSION));

        $newFile = Employees::filePath() . "$id/" . $newFileName . "." . $extension;

        if (rename($oldFile, $newFile)) {
            return response()->json('ok', 200);
        } else {
            return response()->json('Unable to rename file!', 403);
        }
    }

    public function trashFile(Request $request) {
        $id = $request['EmployeeId'];
        $currentFile = $request['CurrentFile'];

        $current = Employees::filePath() . "$id/" . $currentFile;

        $path = Employees::filePath() . "$id/trash/";
        File::makeDirectory($path, $mode = 0777, true, true);
        $trash = $path . date('Y_m_d_H_i_') . $currentFile;

        if (file_exists($current)) {
            if (rename($current, $trash)) {
                return response()->json('ok', 200);
            } else {
                return response()->json('Unable to trash file!', 403);
            }
        } else {
            return response()->json('File not found!', 404);
        }
    }

    public function getEmployeeFullAjax(Request $request) {
        $id = $request['EmployeeId'];

        $data = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->leftJoin('Towns', 'Employees.TownCurrent', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Employees.BarangayCurrent', '=', 'Barangays.id')
            ->select( 
                'Employees.*', 
                'Positions.Position', 
                'Towns.Town as CurrentTown',
                'Barangays.Barangays as CurrentBarangay',
            )
            ->where('Employees.id', $id)
            ->first();

        // check profile pic
        $profilePic = public_path() . "/imgs/profiles/" . $id . ".jpg";

        // Check if the file exists
        if (File::exists($profilePic)) {
            $data->ProfilePicture = $profilePic;
        } else {
            $data->ProfilePicture = null;
        }

        return response()->json($data, 200);
    }

    public function getEmployeesOnLeaveToday(Request $request) {
        $data = DB::table('LeaveDays')
            ->leftJoin('LeaveApplications', 'LeaveDays.LeaveId', '=', 'LeaveApplications.id')
            ->leftJoin('Employees', 'LeaveApplications.EmployeeId', '=', 'Employees.id')
            ->whereRaw("LeaveDays.LeaveDate='" . date('Y-m-d') . "' AND LeaveApplications.Status='APPROVED'")
            ->select(
                'Employees.*',
                'LeaveApplications.Content',
                'LeaveApplications.LeaveType'
            )
            ->get();

        return response()->json($data, 200);
    }

    public function getEmployeesOnTripToday(Request $request) {
        $data = DB::table('TripTickets')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
            ->whereRaw("TripTickets.DateOfTravel='" . date('Y-m-d') . "' AND TripTickets.Status IN ('APPROVED', 'FILED')")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
            )
            ->get();

        foreach($data as $item) {
            $item->Destinations = DB::table('TripTicketDestinations')
                ->whereRaw("TripTicketId='" . $item->id . "'")
                ->orderBy('DestinationAddress')
                ->get();

            $item->Passengers = DB::table('TripTicketPassengers')
                ->leftJoin('Employees', 'TripTicketPassengers.EmployeeId', '=', 'Employees.id')
                ->whereRaw("TripTicketId='" . $item->id . "'")
                ->select(
                    'Employees.FirstName',
                    'Employees.MiddleName',
                    'Employees.LastName',
                    'Employees.Suffix',
                )
                ->get();
        }

        return response()->json($data, 200);
    }

    public function getEmployeesOnOffsetToday(Request $request) {
        $data = DB::table('OffsetApplications')
            ->leftJoin('Employees', 'OffsetApplications.EmployeeId', '=', 'Employees.id')
            ->whereRaw("OffsetApplications.DateOfOffset='" . date('Y-m-d') . "' AND OffsetApplications.Status='APPROVED'")
            ->select(
                'Employees.*',
                'OffsetApplications.DateOfDuty',
                'OffsetApplications.PurposeOfDuty',
            )
            ->get();

        return response()->json($data, 200);
    }
    
    public function getEmployeesOnTravelToday(Request $request) {
        $data = DB::table('TravelOrderEmployees')
            ->leftJoin('Employees', 'TravelOrderEmployees.EmployeeId', '=', 'Employees.id')
            ->leftJoin('TravelOrders', 'TravelOrderEmployees.TravelOrderId', '=', 'TravelOrders.id')
            ->leftJoin('TravelOrderDays', 'TravelOrders.id', '=', 'TravelOrderDays.TravelOrderId')
            ->whereRaw("TravelOrderDays.Day='" . date('Y-m-d') . "' AND TravelOrders.Status='APPROVED'")
            ->select(
                'Employees.*',
                'TravelOrders.Destination',
                'TravelOrders.Purpose',
            )
            ->get();

        return response()->json($data, 200);
    }
    
    public function updateContactNumbers(Request $request) {
        $contact = $request['ContactNumbers'];
        $id = $request['id'];

        Employees::where('id', $id)
            ->update(['ContactNumbers' => $contact]);

        return response()->json('ok', 200);
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

    public function authorizeToDrive(Request $request) {
        $id = $request['id'];
        $auth = $request['AuthorizedToDrive'];

        if (Permission::hasDirectPermission(['god permission', 'employees update'])) {
            $employee = Employees::find($id);
            if ($employee != null) {
                $employee->AuthorizedToDrive = $auth;
                $employee->save();
            }

            return response()->json($employee, 200);
        } else {
            return response()->json('You are not allowed to access this module', 403);
        }
    }
}
