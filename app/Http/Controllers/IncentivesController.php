<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIncentivesRequest;
use App\Http\Requests\UpdateIncentivesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\IncentivesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Employees;
use Flash;

class IncentivesController extends AppBaseController
{
    /** @var IncentivesRepository $incentivesRepository*/
    private $incentivesRepository;

    public function __construct(IncentivesRepository $incentivesRepo)
    {
        $this->middleware('auth');
        $this->incentivesRepository = $incentivesRepo;
    }

    /**
     * Display a listing of the Incentives.
     */
    public function index(Request $request)
    {
        $incentives = $this->incentivesRepository->paginate(10);

        return view('incentives.index')
            ->with('incentives', $incentives);
    }

    /**
     * Show the form for creating a new Incentives.
     */
    public function create()
    {
        return view('incentives.create');
    }

    /**
     * Store a newly created Incentives in storage.
     */
    public function store(CreateIncentivesRequest $request)
    {
        $input = $request->all();

        $incentives = $this->incentivesRepository->create($input);

        Flash::success('Incentives saved successfully.');

        return redirect(route('incentives.index'));
    }

    /**
     * Display the specified Incentives.
     */
    public function show($id)
    {
        $incentives = $this->incentivesRepository->find($id);

        if (empty($incentives)) {
            Flash::error('Incentives not found');

            return redirect(route('incentives.index'));
        }

        return view('incentives.show')->with('incentives', $incentives);
    }

    /**
     * Show the form for editing the specified Incentives.
     */
    public function edit($id)
    {
        $incentives = $this->incentivesRepository->find($id);

        if (empty($incentives)) {
            Flash::error('Incentives not found');

            return redirect(route('incentives.index'));
        }

        return view('incentives.edit')->with('incentives', $incentives);
    }

    /**
     * Update the specified Incentives in storage.
     */
    public function update($id, UpdateIncentivesRequest $request)
    {
        $incentives = $this->incentivesRepository->find($id);

        if (empty($incentives)) {
            Flash::error('Incentives not found');

            return redirect(route('incentives.index'));
        }

        $incentives = $this->incentivesRepository->update($request->all(), $id);

        Flash::success('Incentives updated successfully.');

        return redirect(route('incentives.index'));
    }

    /**
     * Remove the specified Incentives from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $incentives = $this->incentivesRepository->find($id);

        if (empty($incentives)) {
            Flash::error('Incentives not found');

            return redirect(route('incentives.index'));
        }

        $this->incentivesRepository->delete($id);

        Flash::success('Incentives deleted successfully.');

        return redirect(route('incentives.index'));
    }

    public function thirteenthMonthPay(Request $request) {
        return view('/incentives/thirteenth_month_pay');
    }

    public function getThirteenthMonthData(Request $request) {
        $department = $request['Department'];
        $employeeType = $request['EmployeeType'];
        $term = $request['Term'];

        if ($term == date('Y-m-d', strtotime('first day of May ' . date('Y')))) {
            $from = date('Y-m-d', strtotime('January 1 ' . date('Y')));
            $to = date('Y-m-d', strtotime('Last day of June ' . date('Y')));
        } else {
            $from = date('Y-m-d', strtotime('January 1 ' . date('Y')));
            $to = date('Y-m-d', strtotime('Last day of December ' . date('Y')));
        }

        if ($department == 'SUB-OFFICE') {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->select('Employees.FirstName',
                        'Employees.MiddleName',
                        'Employees.LastName',
                        'Employees.Suffix',
                        'Employees.id',
                        'Positions.BasicSalary AS SalaryAmount',
                        'Positions.Level',
                        'Positions.Position',
                        'EmployeesDesignations.Status',
                )
                ->where('EmployeesDesignations.Status', $employeeType)
                ->where('Employees.OfficeDesignation', $department)
                ->whereRaw("(EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->orderBy('Employees.LastName')
                ->get();
        } else {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->select('Employees.FirstName',
                        'Employees.MiddleName',
                        'Employees.LastName',
                        'Employees.Suffix',
                        'Employees.id',
                        'Positions.BasicSalary AS SalaryAmount',
                        'Positions.Level',
                        'Positions.Position',
                        'EmployeesDesignations.Status',
                )
                ->where('EmployeesDesignations.Status', $employeeType)
                ->where('Positions.Department', $department)
                ->whereRaw("Employees.OfficeDesignation NOT IN ('SUB-OFFICE') AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->orderBy('Employees.LastName')
                ->get();
        }

        foreach($employees as $item) {
            $item->SalaryData = DB::table('PayrollExpandedDetails')
                ->whereRaw("EmployeeId='" . $item->id . "' AND (SalaryPeriod BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'SalaryPeriod',
                    'MonthlyWage',
                    'TermWage',
                    'MonthlyWage',
                    'AbsentAmount',
                )
                ->orderBy('SalaryPeriod')
                ->get();

                if ($term == date('Y-m-d', strtotime('first day of May ' . date('Y')))) {
                    $item->AROthers = DB::table('OtherPayrollDeductions')
                        ->whereRaw("Type='13th Month Pay - 1st Half' AND ScheduleDate='" . date('Y-m-d', strtotime('first day of May ' . date('Y'))) . "'")
                        ->get();
                } else {
                    $item->AROthers = DB::table('OtherPayrollDeductions')
                        ->whereRaw("Type='13th Month Pay - 2nd Half' AND ScheduleDate='" . date('Y-m-d', strtotime('first day of October ' . date('Y'))) . "'")
                        ->get();
                }
        }

        $data = [
            'Employees' => $employees,
        ];
        return response()->json($data, 200);
    }
}
