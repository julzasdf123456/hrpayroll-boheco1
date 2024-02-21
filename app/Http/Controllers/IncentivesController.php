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
use App\Models\Incentives;
use App\Models\IncentiveDetails;
use App\Models\IDGenerator;
use App\Models\UserFootprints;
use App\Models\EmployeeIncentiveAnnualProjections;
use App\Models\IncentivesAnnualProjection;
use App\Models\IncentivesYearEndDetails;
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
        $years = DB::table('Incentives')
            ->select('Year')
            ->groupBy('Year')
            ->get();

        foreach($years as $year) {
            $year->Data = Incentives::where('Year', $year->Year)->orderBy('created_at')->get();
        }

        return view('incentives.index', [
            'data' => $years
        ]);
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

        if ($term == date('Y-m-d', strtotime('May 1, ' . date('Y')))) {
            $from = date('Y-m-d', strtotime('January 1 ' . date('Y')));
            $to = date('Y-m-d', strtotime('Last day of June ' . date('Y')));
            $termName = '13th Month Pay - 1st Half';
        } else {
            $from = date('Y-m-d', strtotime('January 1 ' . date('Y')));
            $to = date('Y-m-d', strtotime('Last day of December ' . date('Y')));
            $termName = '13th Month Pay - 2nd Half';
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
                        'EmployeesDesignations.Status'
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

        $incentiveCheck = DB::table('IncentiveDetails')
            ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
            ->whereRaw("IncentiveName='" . $termName . "' AND Department='" . $department . "' AND EmployeeType='" . $employeeType . "' AND Year='" . date('Y') . "'")
            ->first();

        foreach($employees as $item) {
            // IF October, get May Incentive to be deducted
            if ($termName == '13th Month Pay - 2nd Half') {
                $item->FirstTerm = DB::table('IncentiveDetails')
                    ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
                    ->whereRaw("Incentives.Year='" . date('Y') . "' AND IncentiveDetails.EmployeeId='" . $item->id . "' AND Incentives.IncentiveName='13th Month Pay - 1st Half'")
                    ->select('IncentiveDetails.id', 'IncentiveDetails.NetPay')
                    ->first();
            } else {
                $item->FirstTerm = null;
            }
            
            $item->SalaryData = DB::table('PayrollExpandedDetails')
                ->whereRaw("EmployeeId='" . $item->id . "' AND (SalaryPeriod BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'SalaryPeriod',
                    'MonthlyWage',
                    'TermWage',
                    'AbsentAmount',
                )
                ->orderBy('SalaryPeriod')
                ->get();

            if ($term == date('Y-m-d', strtotime('May 1, ' . date('Y')))) {
                $item->AROthers = DB::table('OtherPayrollDeductions')
                    ->whereRaw("EmployeeId='" . $item->id . "' AND Type='13th Month Pay - 1st Half' AND ScheduleDate='" . date('Y-m-d', strtotime('first day of May ' . date('Y'))) . "'")
                    ->get();

                $item->BEMPC = DB::table('BEMPC')
                    ->select('Amount')
                    ->whereRaw("EmployeeId='" . $item->id . "' AND DeductionFor='13th Month Pay - 1st Half' AND Year='" . date('Y') . "'")
                    ->get();
            } else {
                $item->AROthers = DB::table('OtherPayrollDeductions')
                    ->whereRaw("EmployeeId='" . $item->id . "' AND Type='13th Month Pay - 2nd Half' AND ScheduleDate='" . date('Y-m-d', strtotime('first day of October ' . date('Y'))) . "'")
                    ->get();

                $item->BEMPC = DB::table('BEMPC')
                    ->select('Amount')
                    ->whereRaw("EmployeeId='" . $item->id . "' AND DeductionFor='13th Month Pay - 2nd Half' AND Year='" . date('Y') . "'")
                    ->get();
            }
        }

        $data = [
            'Employees' => $employees,
            'IncentiveCheck' => $incentiveCheck,
        ];
        return response()->json($data, 200);
    }

    public function saveThirteenthMonth(Request $request) {
        $data = $request['Data'];
        $department = $request['Department'];
        $term = $request['Term'];
        $employeeType = $request['EmployeeType'];
        $releaseType = null;


        if ($term == date('Y-m-d', strtotime('May 1, ' . date('Y')))) {
            $incentiveName = '13th Month Pay - 1st Half';
            $releaseType = 'Partial';
        } else {
            $incentiveName = '13th Month Pay - 2nd Half';
            $releaseType = 'Full';
        }

        $incentive = Incentives::where('IncentiveName', $incentiveName)
            ->where('Year', date('Y'))
            ->first();

        if ($incentive != null) {
            $id = $incentive->id;

            $incentive->UserId = Auth::id();
        } else {
            $id = IDGenerator::generateID();

            $incentive = new Incentives;
            $incentive->id = $id;
            $incentive->IncentiveName = $incentiveName;
            $incentive->UserId = Auth::id();
            $incentive->Year = date('Y');
            $incentive->ReleaseType = $releaseType;
        }
        $incentive->save();

        // DELETE EXISTING DETAILS FIRST
        IncentiveDetails::where('IncentivesId', $id)
            ->where('EmployeeType', $employeeType)
            ->where('Department', $department)
            ->delete();
        // SAVE ALL DATA
        foreach($data as $item) {
            $details = new IncentiveDetails;
            $details->id = IDGenerator::generateIDandRandString();
            $details->IncentivesId = $id;
            $details->EmployeeId = $item['id'];
            $details->SubTotal = $item['SubTotal'] != null && is_numeric(str_replace(",", "", $item['SubTotal'])) ? str_replace(",", "", $item['SubTotal']) : 0;
            $details->BasicSalary = $item['SalaryAmount'] != null && is_numeric(str_replace(",", "", $item['SalaryAmount'])) ? str_replace(",", "", $item['SalaryAmount']) : 0;
            $details->TermWage = $item['TermWage'] != null && is_numeric(str_replace(",", "", $item['TermWage'])) ? str_replace(",", "", $item['TermWage']) : 0;
            $details->OtherDeductions = $item['AROthers'] != null && is_numeric(str_replace(",", "", $item['AROthers'])) ? str_replace(",", "", $item['AROthers']) : 0;
            $details->BEMPC = $item['BEMPC'] != null && is_numeric(str_replace(",", "", $item['BEMPC'])) ? str_replace(",", "", $item['BEMPC']) : 0;
            $details->NetPay = $item['NetPay'] != null && is_numeric(str_replace(",", "", $item['NetPay'])) ? str_replace(",", "", $item['NetPay']) : 0;
            $details->EmployeeType = $employeeType;
            $details->Department = $department;
            $details->save();
        }

        UserFootprints::log('Generated ' . $incentiveName, "Submitted " . $department . " " . $incentiveName . " draft for " . date('Y') . " for auditing."); 

        return response()->json('ok', 200);
    }

    public function viewIncentives($id) {
        $incentive = Incentives::find($id);

        $departments = DB::table('IncentiveDetails')
            ->whereRaw("IncentivesId='" . $id . "'")
            ->select(
                'Department',
            )
            ->groupBy('Department')
            ->get();

        $datas = [];
        foreach($departments as $item) {
            array_push($datas, [
                'Department' => $item->Department,
                'Data' => DB::table('IncentiveDetails')
                    ->leftJoin('Employees', 'IncentiveDetails.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("IncentiveDetails.Department='" . $item->Department . "' AND IncentiveDetails.IncentivesId='" . $id . "'")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'IncentiveDetails.*'
                    )
                    ->orderBy('LastName')
                    ->get(),
            ]);
        }

        return view('/incentives/view_incentives', [
            'incentive' => $incentive,
            'datas' => $datas,
        ]);
    }

    public function delete(Request $request) {
        $id = $request['id'];

        $incentive = Incentives::where('id', $id)->first();
        IncentiveDetails::where('IncentivesId', $id)->delete();

        UserFootprints::log('Deleted ' . $incentive->IncentiveName, "Deleted " . $incentive->IncentiveName . " data for " . $incentive->Year . "."); 

        $incentive->delete();

        return response()->json('ok', 200);
    }

    public function lock(Request $request) {
        $id = $request['id'];

        $incentive = Incentives::find($id);
        if ($incentive != null) {
            $incentive->Status = 'Locked';
            $incentive->save();

            UserFootprints::log('Locked ' . $incentive->IncentiveName, "Locked " . $incentive->IncentiveName . " data for " . $incentive->Year . "."); 

            // UPDATE ActualAmountReceived in EmployeeProjections
            $iDetails = IncentiveDetails::where('IncentivesId', $id)->get();
            if ($incentive->ReleaseType == 'Partial') {
                foreach($iDetails as $item) {
                    if ($incentive->IncentiveName == '13th Month Pay - 1st Half') {
                        EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                            ->where('EmployeeId', $item->EmployeeId)
                            ->where('Incentive', '13th Month Pay')
                            ->update(['ActualAmountPartialReceived' => $item->NetPay]);
                    } elseif ($incentive->IncentiveName == '13th Month Pay - 2nd Half') {
                        EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                            ->where('EmployeeId', $item->EmployeeId)
                            ->where('Incentive', '13th Month Pay')
                            ->update(['ActualAmountReceived' => $item->NetPay]);
                    } else {
                        EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                            ->where('EmployeeId', $item->EmployeeId)
                            ->where('Incentive', $incentive->IncentiveName)
                            ->update(['ActualAmountPartialReceived' => $item->NetPay]);
                    }
                }
            } else {
                foreach($iDetails as $item) {
                    if ($incentive->IncentiveName == '13th Month Pay - 1st Half') {
                        EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                            ->where('EmployeeId', $item->EmployeeId)
                            ->where('Incentive', '13th Month Pay')
                            ->update(['ActualAmountPartialReceived' => $item->NetPay]);
                    } elseif ($incentive->IncentiveName == '13th Month Pay - 2nd Half') {
                        EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                            ->where('EmployeeId', $item->EmployeeId)
                            ->where('Incentive', '13th Month Pay')
                            ->update(['ActualAmountReceived' => $item->NetPay]);
                    } else {
                        EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                            ->where('EmployeeId', $item->EmployeeId)
                            ->where('Incentive', $incentive->IncentiveName)
                            ->update(['ActualAmountReceived' => $item->NetPay]);
                    }
                }
            }
        }

        return response()->json('ok', 200);
    }

    public function otherBonuses(Request $request) {
        return view('/incentives/other_bonuses', [

        ]);
    }

    public function getIncentivesList(Request $request) {
        $incentives  = IncentivesAnnualProjection::where('Year', date('Y'))
            ->whereNotIn('Incentive', ['Rice and Laundry'])
            ->get();

        return response()->json($incentives, 200);
    }

    public function getCustomIncentivesData(Request $request) {
        $department = $request['Department'];
        $employeeType = $request['EmployeeType'];
        $incentive = $request['Incentive'];
        $releaseType = $request['ReleaseType'];

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
                        'EmployeesDesignations.Status'
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

        $incentiveCheck = DB::table('IncentiveDetails')
            ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
            ->whereRaw("IncentiveName='" . $incentive . "' AND Department='" . $department . "' AND EmployeeType='" . $employeeType . "' AND Year='" . date('Y') . "' AND Incentives.ReleaseType='" . $releaseType . "'")
            ->first();

        foreach($employees as $item) {
            $item->AROthers = DB::table('OtherPayrollDeductions')
                ->whereRaw("EmployeeId='" . $item->id . "' AND Type='" . $incentive . "' AND (created_at BETWEEN '" . date('Y-m-d', strtotime('January 1, ' . date('Y'))) . "' AND '" . date('Y-m-d', strtotime('December 31, ' . date('Y'))) . "')")
                ->get();

            $item->BEMPC = DB::table('BEMPC')
                ->select('Amount')
                ->whereRaw("EmployeeId='" . $item->id . "' AND DeductionFor='" . $incentive . "' AND Year='" . date('Y') . "' AND ReleaseType='" . $releaseType . "'")
                ->get();

            $item->ExistingIncentive = DB::table('IncentiveDetails')
                ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
                ->whereRaw("Incentives.Year='" . date('Y') . "' AND IncentiveDetails.EmployeeId='" . $item->id . "' AND Incentives.IncentiveName='" . $incentive . "' AND Incentives.ReleaseType='" . $releaseType . "'")
                ->select('IncentiveDetails.id', 'IncentiveDetails.NetPay', 'IncentiveDetails.SubTotal')
                ->first();
        }

        $data = [
            'Employees' => $employees,
            'IncentiveCheck' => $incentiveCheck,
        ];

        return response()->json($data, 200);
    }

    public function saveCustomBonus(Request $request) {
        $data = $request['Data'];
        $department = $request['Department'];
        $incentiveName = $request['Incentive'];
        $employeeType = $request['EmployeeType'];
        $notes = $request['Notes'];
        $releaseType = $request['ReleaseType'];

        $incentive = Incentives::where('IncentiveName', $incentiveName)
            ->where('Year', date('Y'))
            ->where('ReleaseType', $releaseType)
            ->first();

        if ($incentive != null) {
            $id = $incentive->id;

            $incentive->UserId = Auth::id();
            $incentive->Notes = $notes;
        } else {
            $id = IDGenerator::generateID();

            $incentive = new Incentives;
            $incentive->id = $id;
            $incentive->IncentiveName = $incentiveName;
            $incentive->UserId = Auth::id();
            $incentive->Year = date('Y');
            $incentive->Notes = $notes;
            $incentive->ReleaseType = $releaseType;
        }
        $incentive->save();

        // DELETE EXISTING DETAILS FIRST
        IncentiveDetails::where('IncentivesId', $id)
            ->where('EmployeeType', $employeeType)
            ->where('Department', $department)
            ->delete();
        // SAVE ALL DATA
        foreach($data as $item) {
            $details = new IncentiveDetails;
            $details->id = IDGenerator::generateIDandRandString();
            $details->IncentivesId = $id;
            $details->EmployeeId = $item['id'];
            $details->SubTotal = $item['BonusAmount'] != null && is_numeric(str_replace(",", "", $item['BonusAmount'])) ? str_replace(",", "", $item['BonusAmount']) : 0;
            $details->BasicSalary = $item['SalaryAmount'] != null && is_numeric(str_replace(",", "", $item['SalaryAmount'])) ? str_replace(",", "", $item['SalaryAmount']) : 0;
            $details->OtherDeductions = $item['AROthers'] != null && is_numeric(str_replace(",", "", $item['AROthers'])) ? str_replace(",", "", $item['AROthers']) : 0;
            $details->BEMPC = $item['BEMPC'] != null && is_numeric(str_replace(",", "", $item['BEMPC'])) ? str_replace(",", "", $item['BEMPC']) : 0;
            $details->NetPay = $item['NetPay'] != null && is_numeric(str_replace(",", "", $item['NetPay'])) ? str_replace(",", "", $item['NetPay']) : 0;
            $details->EmployeeType = $employeeType;
            $details->Department = $department;
            $details->save();
        }

        UserFootprints::log('Generated ' . $incentiveName, "Submitted " . $department . " " . $incentiveName . " draft for " . date('Y') . " for auditing."); 

        return response()->json('ok', 200);
    }

    public function yearEndBonuses(Request $request) {
        return view('/incentives/year_end_bonuses', [

        ]);
    }

    public function getYearEndIncentivesData(Request $request) {
        $department = $request['Department'];
        $employeeType = $request['EmployeeType'];

        $incentiveCheck = DB::table('IncentivesYearEndDetails')
            ->leftJoin('Incentives', 'IncentivesYearEndDetails.IncentivesId', '=', 'Incentives.id')
            ->whereRaw("IncentiveName='Year-end Incentives' AND Department='" . $department . "' AND EmployeeType='" . $employeeType . "' AND Year='" . date('Y') . "' AND Incentives.ReleaseType='Full'")
            ->first();  

        if ($department == 'SUB-OFFICE') {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->select('Employees.FirstName',
                        'Employees.MiddleName',
                        'Employees.LastName',
                        'Employees.Suffix',
                        'Employees.DateHired',
                        'Employees.id',
                        'Positions.BasicSalary AS SalaryAmount',
                        'Positions.Level',
                        'Positions.Position',
                        'EmployeesDesignations.Status'
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
                        'Employees.DateHired',
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

        $from = date('Y-m-d', strtotime('January 1 ' . date('Y')));
        $to = date('Y-m-d', strtotime('Last day of December ' . date('Y')));

        foreach($employees as $item) {
            $item->SalaryData = DB::table('PayrollExpandedDetails')
                ->whereRaw("EmployeeId='" . $item->id . "' AND (SalaryPeriod BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'SalaryPeriod',
                    'MonthlyWage',
                    'TermWage',
                    'AbsentAmount',
                )
                ->orderBy('SalaryPeriod')
                ->get();

            // 13th Month Received
            $item->Received13thMonths = DB::table('IncentiveDetails')
                ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
                ->whereRaw("IncentiveDetails.EmployeeId='" . $item->id . "' AND Incentives.IncentiveName IN ('13th Month Pay - 1st Half', '13th Month Pay - 2nd Half')")
                ->select(
                    'IncentiveName',
                    'NetPay',
                )
                ->orderBy('Incentives.created_at')
                ->get();

            $item->AROthers = DB::table('OtherPayrollDeductions')
                ->whereRaw("EmployeeId='" . $item->id . "' AND Type='Year-end Incentives' AND (created_at BETWEEN '" . date('Y-m-d', strtotime('January 1, ' . date('Y'))) . "' AND '" . date('Y-m-d', strtotime('December 31, ' . date('Y'))) . "')")
                ->get();

            $item->BEMPC = DB::table('BEMPC')
                ->select('Amount')
                ->whereRaw("EmployeeId='" . $item->id . "' AND DeductionFor='Year-end Incentives' AND Year='" . date('Y') . "' AND ReleaseType='Full'")
                ->get();

            // CASH GIFT
            $item->ExistingIncentive = DB::table('IncentiveDetails')
                ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
                ->whereRaw("Incentives.Year='" . date('Y') . "' AND IncentiveDetails.EmployeeId='" . $item->id . "' AND Incentives.IncentiveName='Year-end Incentives' AND Incentives.ReleaseType='Full'")
                ->select('IncentiveDetails.id', 'IncentiveDetails.NetPay', 'IncentiveDetails.SubTotal')
                ->first();

            // VL and SL Conversion
            $item->VLSL = DB::table('LeaveConversions')
                ->whereRaw("Year='" . date('Y') . "' AND EmployeeId='" . $item->id . "' AND Status='Approved'")
                ->get();
        }

        $data = [
            'Employees' => $employees,
            'IncentiveCheck' => $incentiveCheck,
        ];

        return response()->json($data, 200);
    }

    public function viewYearEndIncentives($id) {
        $incentive = Incentives::find($id);

        $departments = DB::table('IncentivesYearEndDetails')
            ->whereRaw("IncentivesId='" . $id . "'")
            ->select(
                'Department',
            )
            ->groupBy('Department')
            ->get();

        $datas = [];
        foreach($departments as $item) {
            array_push($datas, [
                'Department' => $item->Department,
                'Data' => DB::table('IncentivesYearEndDetails')
                    ->leftJoin('Employees', 'IncentivesYearEndDetails.EmployeeId', '=', 'Employees.id')
                    ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                    ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                    ->whereRaw("IncentivesYearEndDetails.Department='" . $item->Department . "' AND IncentivesYearEndDetails.IncentivesId='" . $id . "'")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'IncentivesYearEndDetails.*',
                        'Positions.Position'
                    )
                    ->orderBy('LastName')
                    ->get(),
            ]);
        }

        return view('/incentives/view_year_end_incentives', [
            'incentive' => $incentive,
            'datas' => $datas,
        ]);
    }

    public function lockYearEndIncentives(Request $request) {
        $id = $request['id'];

        $incentive = Incentives::find($id);
        if ($incentive != null) {
            $incentive->Status = 'Locked';
            $incentive->save();

            UserFootprints::logSource('Locked Year-end Incentives', "Locked Year-end Incentives data for " . $incentive->Year . ".", $incentive->id); 

            // UPDATE ActualAmountReceived in EmployeeProjections
            $iDetails = IncentivesYearEndDetails::where('IncentivesId', $id)->get();
            foreach($iDetails as $item) {
                // 14th Month
                if ($item->FourteenthMonthPay != null && $item->FourteenthMonthPay !== 0) {
                    EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                        ->where('EmployeeId', $item->EmployeeId)
                        ->where('Incentive', '14th Month Pay')
                        ->update(['ActualAmountReceived' => $item->FourteenthMonthPay]);
                }
                
                // 13th Month Differential
                if ($item->FourteenthMonthPay != null && $item->FourteenthMonthPay !== 0) {
                    EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                        ->where('EmployeeId', $item->EmployeeId)
                        ->where('Incentive', '13th Month Pay')
                        ->update(['Differential' => $item->ThirteenthMonthDifferential]);
                }

                // Cash Gift
                if ($item->FourteenthMonthPay != null && $item->FourteenthMonthPay !== 0) {
                    EmployeeIncentiveAnnualProjections::where('Year', $incentive->Year)
                        ->where('EmployeeId', $item->EmployeeId)
                        ->where('Incentive', 'Cash Gift')
                        ->update(['ActualAmountReceived' => $item->CashGift]);
                }
            }
        }

        return response()->json('ok', 200);
    }
}
