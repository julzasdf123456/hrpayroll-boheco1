<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeePayrollSundriesRequest;
use App\Http\Requests\UpdateEmployeePayrollSundriesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EmployeePayrollSundriesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\IDGenerator;
use App\Models\EmployeePayrollSundries;
use Flash;

class EmployeePayrollSundriesController extends AppBaseController
{
    /** @var EmployeePayrollSundriesRepository $employeePayrollSundriesRepository*/
    private $employeePayrollSundriesRepository;

    public function __construct(EmployeePayrollSundriesRepository $employeePayrollSundriesRepo)
    {
        $this->middleware('auth');
        $this->employeePayrollSundriesRepository = $employeePayrollSundriesRepo;
    }

    /**
     * Display a listing of the EmployeePayrollSundries.
     */
    public function index(Request $request)
    {
        $employeePayrollSundries = $this->employeePayrollSundriesRepository->paginate(10);

        return view('employee_payroll_sundries.index')
            ->with('employeePayrollSundries', $employeePayrollSundries);
    }

    /**
     * Show the form for creating a new EmployeePayrollSundries.
     */
    public function create()
    {
        return view('employee_payroll_sundries.create');
    }

    /**
     * Store a newly created EmployeePayrollSundries in storage.
     */
    public function store(CreateEmployeePayrollSundriesRequest $request)
    {
        $input = $request->all();

        $employeePayrollSundries = $this->employeePayrollSundriesRepository->create($input);

        Flash::success('Employee Payroll Sundries saved successfully.');

        return redirect(route('employeePayrollSundries.index'));
    }

    /**
     * Display the specified EmployeePayrollSundries.
     */
    public function show($id)
    {
        $employeePayrollSundries = $this->employeePayrollSundriesRepository->find($id);

        if (empty($employeePayrollSundries)) {
            Flash::error('Employee Payroll Sundries not found');

            return redirect(route('employeePayrollSundries.index'));
        }

        return view('employee_payroll_sundries.show')->with('employeePayrollSundries', $employeePayrollSundries);
    }

    /**
     * Show the form for editing the specified EmployeePayrollSundries.
     */
    public function edit($id)
    {
        $employeePayrollSundries = $this->employeePayrollSundriesRepository->find($id);

        if (empty($employeePayrollSundries)) {
            Flash::error('Employee Payroll Sundries not found');

            return redirect(route('employeePayrollSundries.index'));
        }

        return view('employee_payroll_sundries.edit')->with('employeePayrollSundries', $employeePayrollSundries);
    }

    /**
     * Update the specified EmployeePayrollSundries in storage.
     */
    public function update($id, UpdateEmployeePayrollSundriesRequest $request)
    {
        $employeePayrollSundries = $this->employeePayrollSundriesRepository->find($id);

        if (empty($employeePayrollSundries)) {
            Flash::error('Employee Payroll Sundries not found');

            return redirect(route('employeePayrollSundries.index'));
        }

        $employeePayrollSundries = $this->employeePayrollSundriesRepository->update($request->all(), $id);

        Flash::success('Employee Payroll Sundries updated successfully.');

        return redirect(route('employeePayrollSundries.index'));
    }

    /**
     * Remove the specified EmployeePayrollSundries from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $employeePayrollSundries = $this->employeePayrollSundriesRepository->find($id);

        if (empty($employeePayrollSundries)) {
            Flash::error('Employee Payroll Sundries not found');

            return redirect(route('employeePayrollSundries.index'));
        }

        $this->employeePayrollSundriesRepository->delete($id);

        Flash::success('Employee Payroll Sundries deleted successfully.');

        return redirect(route('employeePayrollSundries.index'));
    }

    public function contributions(Request $request) {
        return view('/employee_payroll_sundries/contributions', [

        ]);
    }

    public function getContributionData(Request $request) {
        $department = $request['Department'];

        if ($department == 'All') {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeePayrollSundries', 'EmployeePayrollSundries.EmployeeId', '=', 'Employees.id')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->select(
                    'Employees.id AS EmployeeIdNumber',
                    'FirstName',
                    'MiddleName',
                    'LastName',
                    'Suffix',
                    'EmployeePayrollSundries.*'
                )
                ->orderBy('FirstName')
                ->get();
        } else {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeePayrollSundries', 'EmployeePayrollSundries.EmployeeId', '=', 'Employees.id')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->whereRaw("Positions.Department='" . $department . "'")
                ->select(
                    'Employees.id AS EmployeeIdNumber',
                    'FirstName',
                    'MiddleName',
                    'LastName',
                    'Suffix',
                    'EmployeePayrollSundries.*'
                )
                ->orderBy('FirstName')
                ->get();
        }

        return response()->json($employees, 200);
    }

    public function insertContributionData(Request $request) {
        $employeeId = $request['EmployeeId'];
        $amount = $request['Amount'];
        $type = $request['Type'];

        $sundry = EmployeePayrollSundries::where('EmployeeId', $employeeId)->first();
        if ($sundry == null) {
            $sundry = new EmployeePayrollSundries;
            $sundry->id = IDGenerator::generateIDandRandString();
            $sundry->EmployeeId = $employeeId;
        }

        if ($type == 'PagIbigEmployer') {
            $sundry->PagIbigContributionEmployer = $amount;
        } elseif ($type == 'PagIbigEmployee') {
            $sundry->PagIbigContribution = $amount;
        } elseif ($type == 'SSSEmployer') {
            $sundry->SSSContributionEmployer = $amount;
        } elseif ($type == 'SSSEmployee') {
            $sundry->SSSContribution = $amount;
        } elseif ($type == 'PhilHealthEmployer') {
            $sundry->PhilHealth = $amount;
        } elseif ($type == 'PhilHealthEmployee') {
            $sundry->PhilHealthContributionEmployer = $amount;
        }

        $sundry->save();
    }

    public function insertAllContributionData(Request $request) {
        $amount = $request['Amount'];
        $type = $request['Type'];
        $department = $request['Department'];

        $employees = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->whereRaw("Positions.Department='" . $department . "'")
                ->select(
                    'Employees.id'
                )
                ->get();

        foreach($employees as $item) {
            $sundry = EmployeePayrollSundries::where('EmployeeId', $item->id)->first();
            if ($sundry == null) {
                $sundry = new EmployeePayrollSundries;
                $sundry->id = IDGenerator::generateIDandRandString();
                $sundry->EmployeeId = $item->id;
            }

            if ($type == 'PagIbigEmployer') {
                $sundry->PagIbigContributionEmployer = $amount;
            } elseif ($type == 'PagIbigEmployee') {
                $sundry->PagIbigContribution = $amount;
            } elseif ($type == 'SSSEmployer') {
                $sundry->SSSContributionEmployer = $amount;
            } elseif ($type == 'SSSEmployee') {
                $sundry->SSSContribution = $amount;
            } elseif ($type == 'PhilHealthEmployer') {
                $sundry->PhilHealth = $amount;
            } elseif ($type == 'PhilHealthEmployee') {
                $sundry->PhilHealthContributionEmployer = $amount;
            }

            $sundry->save();
        }

        return response()->json('ok', 200);
    }
}