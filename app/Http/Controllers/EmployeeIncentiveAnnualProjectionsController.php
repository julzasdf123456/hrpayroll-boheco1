<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeIncentiveAnnualProjectionsRequest;
use App\Http\Requests\UpdateEmployeeIncentiveAnnualProjectionsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EmployeeIncentiveAnnualProjectionsRepository;
use Illuminate\Http\Request;
use App\Models\EmployeeIncentiveAnnualProjections;
use App\Models\IDGenerator;
use App\Models\Employees;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class EmployeeIncentiveAnnualProjectionsController extends AppBaseController
{
    /** @var EmployeeIncentiveAnnualProjectionsRepository $employeeIncentiveAnnualProjectionsRepository*/
    private $employeeIncentiveAnnualProjectionsRepository;

    public function __construct(EmployeeIncentiveAnnualProjectionsRepository $employeeIncentiveAnnualProjectionsRepo)
    {
        $this->middleware('auth');
        $this->employeeIncentiveAnnualProjectionsRepository = $employeeIncentiveAnnualProjectionsRepo;
    }

    /**
     * Display a listing of the EmployeeIncentiveAnnualProjections.
     */
    public function index(Request $request)
    {
        $employeeIncentiveAnnualProjections = $this->employeeIncentiveAnnualProjectionsRepository->paginate(10);

        return view('employee_incentive_annual_projections.index')
            ->with('employeeIncentiveAnnualProjections', $employeeIncentiveAnnualProjections);
    }

    /**
     * Show the form for creating a new EmployeeIncentiveAnnualProjections.
     */
    public function create()
    {
        return view('employee_incentive_annual_projections.create');
    }

    /**
     * Store a newly created EmployeeIncentiveAnnualProjections in storage.
     */
    public function store(CreateEmployeeIncentiveAnnualProjectionsRequest $request)
    {
        $input = $request->all();

        $employeeIncentiveAnnualProjections = $this->employeeIncentiveAnnualProjectionsRepository->create($input);

        Flash::success('Employee Incentive Annual Projections saved successfully.');

        return redirect(route('employeeIncentiveAnnualProjections.index'));
    }

    /**
     * Display the specified EmployeeIncentiveAnnualProjections.
     */
    public function show($id)
    {
        $employeeIncentiveAnnualProjections = $this->employeeIncentiveAnnualProjectionsRepository->find($id);

        if (empty($employeeIncentiveAnnualProjections)) {
            Flash::error('Employee Incentive Annual Projections not found');

            return redirect(route('employeeIncentiveAnnualProjections.index'));
        }

        return view('employee_incentive_annual_projections.show')->with('employeeIncentiveAnnualProjections', $employeeIncentiveAnnualProjections);
    }

    /**
     * Show the form for editing the specified EmployeeIncentiveAnnualProjections.
     */
    public function edit($id)
    {
        $employeeIncentiveAnnualProjections = $this->employeeIncentiveAnnualProjectionsRepository->find($id);

        if (empty($employeeIncentiveAnnualProjections)) {
            Flash::error('Employee Incentive Annual Projections not found');

            return redirect(route('employeeIncentiveAnnualProjections.index'));
        }

        return view('employee_incentive_annual_projections.edit')->with('employeeIncentiveAnnualProjections', $employeeIncentiveAnnualProjections);
    }

    /**
     * Update the specified EmployeeIncentiveAnnualProjections in storage.
     */
    public function update($id, UpdateEmployeeIncentiveAnnualProjectionsRequest $request)
    {
        $employeeIncentiveAnnualProjections = $this->employeeIncentiveAnnualProjectionsRepository->find($id);

        if (empty($employeeIncentiveAnnualProjections)) {
            Flash::error('Employee Incentive Annual Projections not found');

            return redirect(route('employeeIncentiveAnnualProjections.index'));
        }

        $employeeIncentiveAnnualProjections = $this->employeeIncentiveAnnualProjectionsRepository->update($request->all(), $id);

        Flash::success('Employee Incentive Annual Projections updated successfully.');

        return redirect(route('employeeIncentiveAnnualProjections.index'));
    }

    /**
     * Remove the specified EmployeeIncentiveAnnualProjections from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $employeeIncentiveAnnualProjections = $this->employeeIncentiveAnnualProjectionsRepository->find($id);

        if (empty($employeeIncentiveAnnualProjections)) {
            Flash::error('Employee Incentive Annual Projections not found');

            return redirect(route('employeeIncentiveAnnualProjections.index'));
        }

        $this->employeeIncentiveAnnualProjectionsRepository->delete($id);

        Flash::success('Employee Incentive Annual Projections deleted successfully.');

        return redirect(route('employeeIncentiveAnnualProjections.index'));
    }

    public function projectAll(Request $request) {
        $data = $request['Data'];
        $year = $request['Year'];

        $employees = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->leftJoin('PayrollSchedules', 'Employees.PayrollScheduleId', '=', 'PayrollSchedules.id')
            ->whereRaw("Employees.EmploymentStatus IS NULL OR Employees.EmploymentStatus NOT IN ('Retired', 'Resigned')")
            ->select('Employees.*',
                    'Positions.BasicSalary AS SalaryAmount',
                    'Positions.Level',
            )
            ->get();

        // DELETE EXISTING Incentives FIRST
        EmployeeIncentiveAnnualProjections::where('Year', $year)
            ->delete();

        // LOOP ALL EMPLOYEES
        foreach($employees as $employee) {
            // LOOP EACH INCENTIVE
            foreach($data as $item) {
                // CHECK IF ALREADY EXISTS IN EmployeeIncentiveAnnualProjections
                $incentive = new EmployeeIncentiveAnnualProjections;
                $incentive->id = IDGenerator::generateIDandRandString();
                $incentive->Year = $year;
                $incentive->EmployeeId = $employee->id;
                $incentive->DeductMonthly = 'Yes';
                $incentive->Incentive = $item['Incentive'];
                $incentive->Amount = $item['Amount'];
                $incentive->MaxUntaxableAmount = $item['MaxUntaxableThreshold'];
                $incentive->IsTaxable = $item['Taxable'];
                $incentive->save();
            }

            // ADD OTHER INCENTIVE PROJECTIONS
            $basicSalary = $employee->SalaryAmount != null ? floatval($employee->SalaryAmount) : 0;
            // 13th MONTH PAY
            $incentive = new EmployeeIncentiveAnnualProjections;
            $incentive->id = IDGenerator::generateIDandRandString();
            $incentive->Year = $year;
            $incentive->EmployeeId = $employee->id;
            $incentive->DeductMonthly = 'Yes';           
            $incentive->Incentive = '13th Month Pay';
            $incentive->Amount = $basicSalary;
            $incentive->MaxUntaxableAmount = 0;
            $incentive->IsTaxable = 'true';
            $incentive->save();

            // 14th MONTH PAY
            $incentive = new EmployeeIncentiveAnnualProjections;
            $incentive->id = IDGenerator::generateIDandRandString();
            $incentive->Year = $year;
            $incentive->EmployeeId = $employee->id;
            $incentive->DeductMonthly = 'Yes';
            $incentive->Incentive = '14th Month Pay';
            $incentive->Amount = $basicSalary;
            $incentive->MaxUntaxableAmount = 0;
            $incentive->IsTaxable = 'true';
            $incentive->save();

            // Representation Allowances
            if (in_array($employee->Level, ['Chief', 'Manager', 'General Manager'])) {
                $incentive = new EmployeeIncentiveAnnualProjections;
                $incentive->id = IDGenerator::generateIDandRandString();
                $incentive->Year = $year;
                $incentive->EmployeeId = $employee->id;
                $incentive->DeductMonthly = 'Yes';
                $incentive->Incentive = 'Representation Allowances';
                $incentive->Amount = 8000;
                $incentive->MaxUntaxableAmount = 0;
                $incentive->IsTaxable = 'true';
                $incentive->save();
            }
            

            // LONGEVITY
            if ($employee->DateHired != null) {
                $longevity = Employees::getTotalLongevityProjection($employee, $year);
                if ($longevity > 0) {
                    $incentive = new EmployeeIncentiveAnnualProjections;
                    $incentive->id = IDGenerator::generateIDandRandString();
                    $incentive->Year = $year;
                    $incentive->EmployeeId = $employee->id;
                    $incentive->DeductMonthly = 'Yes';
                    $incentive->Incentive = 'Longevity';
                    $incentive->Amount = $longevity;
                    $incentive->MaxUntaxableAmount = 0;
                    $incentive->IsTaxable = 'true';
                    $incentive->save();
                }
            }            
        }

        return response()->json('ok', 200);
    }
}
