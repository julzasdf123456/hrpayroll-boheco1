<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayrollExpandedDetailsRequest;
use App\Http\Requests\UpdatePayrollExpandedDetailsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PayrollExpandedDetailsRepository;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\UserFootprints;
use App\Models\PayrollExpandedDetails;
use App\Models\EmployeeIncntvsProjectionTaxMark;
use App\Models\EmployeeIncentiveAnnualProjections;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;

class PayrollExpandedDetailsController extends AppBaseController
{
    /** @var PayrollExpandedDetailsRepository $payrollExpandedDetailsRepository*/
    private $payrollExpandedDetailsRepository;

    public function __construct(PayrollExpandedDetailsRepository $payrollExpandedDetailsRepo)
    {
        $this->middleware('auth');
        $this->payrollExpandedDetailsRepository = $payrollExpandedDetailsRepo;
    }

    /**
     * Display a listing of the PayrollExpandedDetails.
     */
    public function index(Request $request)
    {
        $payrollExpandedDetails = $this->payrollExpandedDetailsRepository->paginate(10);

        return view('payroll_expanded_details.index')
            ->with('payrollExpandedDetails', $payrollExpandedDetails);
    }

    /**
     * Show the form for creating a new PayrollExpandedDetails.
     */
    public function create()
    {
        return view('payroll_expanded_details.create');
    }

    /**
     * Store a newly created PayrollExpandedDetails in storage.
     */
    public function store(CreatePayrollExpandedDetailsRequest $request)
    {
        $input = $request->all();

        $payrollExpandedDetails = $this->payrollExpandedDetailsRepository->create($input);

        Flash::success('Payroll Expanded Details saved successfully.');

        return redirect(route('payrollExpandedDetails.index'));
    }

    /**
     * Display the specified PayrollExpandedDetails.
     */
    public function show($id)
    {
        $payrollExpandedDetails = $this->payrollExpandedDetailsRepository->find($id);

        if (empty($payrollExpandedDetails)) {
            Flash::error('Payroll Expanded Details not found');

            return redirect(route('payrollExpandedDetails.index'));
        }

        return view('payroll_expanded_details.show')->with('payrollExpandedDetails', $payrollExpandedDetails);
    }

    /**
     * Show the form for editing the specified PayrollExpandedDetails.
     */
    public function edit($id)
    {
        $payrollExpandedDetails = $this->payrollExpandedDetailsRepository->find($id);

        if (empty($payrollExpandedDetails)) {
            Flash::error('Payroll Expanded Details not found');

            return redirect(route('payrollExpandedDetails.index'));
        }

        return view('payroll_expanded_details.edit')->with('payrollExpandedDetails', $payrollExpandedDetails);
    }

    /**
     * Update the specified PayrollExpandedDetails in storage.
     */
    public function update($id, UpdatePayrollExpandedDetailsRequest $request)
    {
        $payrollExpandedDetails = $this->payrollExpandedDetailsRepository->find($id);

        if (empty($payrollExpandedDetails)) {
            Flash::error('Payroll Expanded Details not found');

            return redirect(route('payrollExpandedDetails.index'));
        }

        $payrollExpandedDetails = $this->payrollExpandedDetailsRepository->update($request->all(), $id);

        Flash::success('Payroll Expanded Details updated successfully.');

        return redirect(route('payrollExpandedDetails.index'));
    }

    /**
     * Remove the specified PayrollExpandedDetails from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $payrollExpandedDetails = $this->payrollExpandedDetailsRepository->find($id);

        if (empty($payrollExpandedDetails)) {
            Flash::error('Payroll Expanded Details not found');

            return redirect(route('payrollExpandedDetails.index'));
        }

        $this->payrollExpandedDetailsRepository->delete($id);

        Flash::success('Payroll Expanded Details deleted successfully.');

        return redirect(route('payrollExpandedDetails.index'));
    }

    public function bulkSavePayroll(Request $request) {
        $data = $request['Data'];
        $department = $request['Department'];
        $salaryPeriod = $request['SalaryPeriod'];
        $employeeType = $request['EmployeeType'];

        // DELETE EXISTING FIRST
        PayrollExpandedDetails::where('Department', $department)
            ->where('SalaryPeriod', $salaryPeriod)
            ->where('EmployeeType', $employeeType)
            ->delete();

        // DELETE EmployeeIncntvsProjectionTaxMark FIRST
        EmployeeIncntvsProjectionTaxMark::where('Department', $department)
            ->where('SalaryPeriod', $salaryPeriod)
            ->where('EmployeeType', $employeeType)
            ->delete();

        foreach($data as $item) {
            $payrollDraft = new PayrollExpandedDetails;
            $payrollDraft->id = IDGenerator::generateIDandRandString();
            $payrollDraft->EmployeeId = $item['EmployeeId'];
            $payrollDraft->SalaryPeriod = $item['SalaryPeriod'];
            $payrollDraft->From = $item['From'];
            $payrollDraft->To = $item['To'];
            $payrollDraft->TotalHoursRendered = $item['TotalHoursRendered'];
            $payrollDraft->TotalWorkedHours = $item['TotalWorkedHours'];
            $payrollDraft->MonthlyWage = $item['MonthlyWage'];
            $payrollDraft->TermWage = $item['TermWage'];
            $payrollDraft->OvertimeHours = $item['OvertimeHours'];
            $payrollDraft->OvertimeAmount = $item['OvertimeAmount'];
            $payrollDraft->AbsentHours = $item['AbsentHours'];
            $payrollDraft->AbsentAmount = $item['AbsentAmount'];
            $payrollDraft->Longevity = $item['Longevity'];
            $payrollDraft->RiceLaundry = $item['RiceLaundry'];
            $payrollDraft->OtherSalaryAdditions = $item['OtherSalaryAdditions'];
            $payrollDraft->OtherSalaryDeductions = $item['OtherSalaryDeductions'];
            $payrollDraft->TotalPartialAmount = $item['TotalPartialAmount'];
            $payrollDraft->MotorycleLoan = $item['MotorycleLoan'];
            $payrollDraft->PagIbigContribution = $item['PagIbigContribution'];
            $payrollDraft->PagIbigLoan = $item['PagIbigLoan'];
            $payrollDraft->SSSContribution = $item['SSSContribution'];
            $payrollDraft->SSSLoan = $item['SSSLoan'];
            $payrollDraft->PhilHealthContribution = $item['PhilHealthContribution'];
            $payrollDraft->OtherDeductions = $item['OtherDeductions'];
            $payrollDraft->SalaryWithholdingTax = $item['SalaryWithholdingTax'];
            $payrollDraft->TotalWithholdingTax = $item['TotalWithholdingTax'];
            $payrollDraft->TotalDeductions = $item['TotalDeductions'];
            $payrollDraft->NetPay = $item['NetPay'];
            $payrollDraft->GeneratedBy = Auth::id();
            $payrollDraft->GeneratedDate = date('Y-m-d H:i:s');
            $payrollDraft->Status = $item['Status'];
            $payrollDraft->Department = $item['Department'];
            $payrollDraft->EmployeeType = $item['EmployeeType'];
            $payrollDraft->save();

            // ADD INCENTIVE PROJECTION TAX COMPUTATION
            $incentiveProjections = EmployeeIncentiveAnnualProjections::where('EmployeeId', $item['EmployeeId'])
                ->where('Year', date('Y', strtotime($salaryPeriod)))
                ->where('DeductMonthly', 'Yes')
                ->get();
            foreach($incentiveProjections as $incentive) {
                $incentiveTax = new EmployeeIncntvsProjectionTaxMark;
                $incentiveTax->id = IDGenerator::generateIDandRandString();
                $incentiveTax->EmployeeId = $item['EmployeeId'];
                $incentiveTax->Incentive = $incentive->Incentive;
                $incentiveTax->Amount = $incentive->Amount;
                $incentiveTax->SalaryPeriod = $item['SalaryPeriod'];
                $incentiveTax->Deducted = 'Yes';
                $incentiveTax->Department = $item['Department'];
                $incentiveTax->EmployeeType = $item['EmployeeType'];
                $incentiveTax->save();
            }
        }

        UserFootprints::log('Generated Payroll Draft', "Submitted " . $department . " payroll draft for salary period " . date('F d, Y', strtotime($salaryPeriod)) . " for auditing.");  

        return response()->json('ok', 200);
    }
}
