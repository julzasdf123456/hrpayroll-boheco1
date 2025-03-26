<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeePayrollSchedulesRequest;
use App\Http\Requests\UpdateEmployeePayrollSchedulesRequest;
use App\Repositories\EmployeePayrollSchedulesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\PayrollSchedules;
use App\Models\IDGenerator;
use App\Models\Employees;
use App\Models\EmployeePayrollSchedules;
use App\Models\DayOffSchedules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class EmployeePayrollSchedulesController extends AppBaseController
{
    /** @var  EmployeePayrollSchedulesRepository */
    private $employeePayrollSchedulesRepository;

    public function __construct(EmployeePayrollSchedulesRepository $employeePayrollSchedulesRepo)
    {
        $this->middleware('auth');
        $this->employeePayrollSchedulesRepository = $employeePayrollSchedulesRepo;
    }

    /**
     * Display a listing of the EmployeePayrollSchedules.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $department = $request['Department'];
        $nameSearch = $request['Name'];

        $departments = DB::table('Positions')
            ->select('Department')
            ->groupBy('Department')
            ->get();

        $schedules = PayrollSchedules::orderBy('Name')->get();
        $dayOffs = DayOffSchedules::orderBy('Days')->get();

        // if ($department != null) {
        //     $employees = DB::table('Employees')
        //         ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
        //         ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
        //         ->whereRaw("Positions.Department='" . $department . "' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
        //         ->select('Employees.*', 'Positions.Department', 'Positions.Position')
        //         ->orderBy("Employees.LastName");
        //         // ->get();
        // } else {
        //     $employees = DB::table('Employees')
        //         ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
        //         ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
        //         ->whereRaw("(EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
        //         ->select('Employees.*', 'Positions.Department', 'Positions.Position')
        //         ->orderBy("Employees.LastName");
        //         // ->get();
        // }

        // if ($nameSearch != null) {
        //     $employees = $employees->where('Employees.FirstName', 'like', "%{$nameSearch}%")
        //         ->orWhere('Employees.LastName', 'like', "%{$nameSearch}%")
        //         ->orWhere('Employees.MiddleName', 'like', "%{$nameSearch}%")
        //         ->orWhere('Employees.Suffix', 'like', "%{$nameSearch}%");
        // }

        // $employees = $employees->paginate(20);


        if ($department != null) {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
                ->whereRaw("Positions.Department='" . $department . "' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->select('Employees.*', 'Positions.Department', 'Positions.Position')
                ->orderBy("Employees.LastName")
                ->get();
        } else {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
                ->whereRaw("(EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->select('Employees.*', 'Positions.Department', 'Positions.Position')
                ->orderBy("Employees.LastName")
                ->get();
        }

        return view('employee_payroll_schedules.index', [
            'departments' => $departments,
            'employees' => $employees,
            'schedules' => $schedules,
            'dayOffs' => $dayOffs,
        ]);
    }

    /**
     * Show the form for creating a new EmployeePayrollSchedules.
     *
     * @return Response
     */
    public function create()
    {
        return view('employee_payroll_schedules.create');
    }

    /**
     * Store a newly created EmployeePayrollSchedules in storage.
     *
     * @param CreateEmployeePayrollSchedulesRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeePayrollSchedulesRequest $request)
    {
        $input = $request->all();

        $employeePayrollSchedules = $this->employeePayrollSchedulesRepository->create($input);

        // Flash::success('Employee Payroll Schedules saved successfully.');
        Employees::where('EmployeeId', $employeePayrollSchedules->EmployeeId)
            ->update(['PayrollScheduleId' => $employeePayrollSchedules->id]);

        return response()->json($employeePayrollSchedules, 200);
    }

    /**
     * Display the specified EmployeePayrollSchedules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $employeePayrollSchedules = $this->employeePayrollSchedulesRepository->find($id);

        if (empty($employeePayrollSchedules)) {
            Flash::error('Employee Payroll Schedules not found');

            return redirect(route('employeePayrollSchedules.index'));
        }

        return view('employee_payroll_schedules.show')->with('employeePayrollSchedules', $employeePayrollSchedules);
    }

    /**
     * Show the form for editing the specified EmployeePayrollSchedules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $employeePayrollSchedules = $this->employeePayrollSchedulesRepository->find($id);

        if (empty($employeePayrollSchedules)) {
            Flash::error('Employee Payroll Schedules not found');

            return redirect(route('employeePayrollSchedules.index'));
        }

        return view('employee_payroll_schedules.edit')->with('employeePayrollSchedules', $employeePayrollSchedules);
    }

    /**
     * Update the specified EmployeePayrollSchedules in storage.
     *
     * @param int $id
     * @param UpdateEmployeePayrollSchedulesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmployeePayrollSchedulesRequest $request)
    {
        $employeePayrollSchedules = $this->employeePayrollSchedulesRepository->find($id);

        if (empty($employeePayrollSchedules)) {
            Flash::error('Employee Payroll Schedules not found');

            return redirect(route('employeePayrollSchedules.index'));
        }

        $employeePayrollSchedules = $this->employeePayrollSchedulesRepository->update($request->all(), $id);

        Flash::success('Employee Payroll Schedules updated successfully.');

        return redirect(route('employeePayrollSchedules.index'));
    }

    /**
     * Remove the specified EmployeePayrollSchedules from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $employeePayrollSchedules = $this->employeePayrollSchedulesRepository->find($id);

        if (empty($employeePayrollSchedules)) {
            Flash::error('Employee Payroll Schedules not found');

            return redirect(route('employeePayrollSchedules.index'));
        }

        $this->employeePayrollSchedulesRepository->delete($id);

        Flash::success('Employee Payroll Schedules deleted successfully.');

        return redirect(route('employeePayrollSchedules.index'));
    }

    public function createSchedule(Request $request) {
        $employeeId = $request['EmployeeId'];
        $scheduleId = $request['ScheduleId'];
    
        $sched = new EmployeePayrollSchedules;
        $sched->id = IDGenerator::generateID();
        $sched->EmployeeId = $employeeId;
        $sched->ScheduleId = $scheduleId;
        $sched->save();

        Employees::where('id', $employeeId)
            ->update(['PayrollScheduleId' => $scheduleId]);

        return response()->json($sched, 200);
    }

    public function updateDayOff(Request $request) {
        $id = $request['id'];
        $dayOff = $request['DayOff'];

        $employee = Employees::find($id);
        if ($employee != null) {
            $employee->DayOffDates = $dayOff;
            $employee->save();
        }

        return response()->json($employee, 200);
    }
}
