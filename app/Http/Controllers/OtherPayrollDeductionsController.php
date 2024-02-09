<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOtherPayrollDeductionsRequest;
use App\Http\Requests\UpdateOtherPayrollDeductionsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\OtherPayrollDeductionsRepository;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\DB;
use App\Models\OtherPayrollDeductions;
use App\Models\UserFootprints;
use Flash;

class OtherPayrollDeductionsController extends AppBaseController
{
    /** @var OtherPayrollDeductionsRepository $otherPayrollDeductionsRepository*/
    private $otherPayrollDeductionsRepository;

    public function __construct(OtherPayrollDeductionsRepository $otherPayrollDeductionsRepo)
    {
        $this->middleware('auth');
        $this->otherPayrollDeductionsRepository = $otherPayrollDeductionsRepo;
    }

    /**
     * Display a listing of the OtherPayrollDeductions.
     */
    public function index(Request $request)
    {
        $otherPayrollDeductions = DB::table('OtherPayrollDeductions')
            ->leftJoin('Employees', 'OtherPayrollDeductions.EmployeeId', '=', 'Employees.id')
            ->whereRaw("ScheduleDate >= GETDATE() AND Type='Others'")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'OtherPayrollDeductions.*'
            )
            ->orderByDesc('OtherPayrollDeductions.created_at')
            ->paginate(30);

        return view('other_payroll_deductions.index')
            ->with('otherPayrollDeductions', $otherPayrollDeductions);
    }

    /**
     * Show the form for creating a new OtherPayrollDeductions.
     */
    public function create()
    {
        return view('other_payroll_deductions.create');
    }

    /**
     * Store a newly created OtherPayrollDeductions in storage.
     */
    public function store(CreateOtherPayrollDeductionsRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateIDandRandString();

        $otherPayrollDeductions = $this->otherPayrollDeductionsRepository->create($input);

        // Flash::success('Other Payroll Deductions saved successfully.');

        // return redirect(route('otherPayrollDeductions.index'));
        return response()->json($otherPayrollDeductions, 200);
    }

    /**
     * Display the specified OtherPayrollDeductions.
     */
    public function show($id)
    {
        $otherPayrollDeductions = $this->otherPayrollDeductionsRepository->find($id);

        if (empty($otherPayrollDeductions)) {
            Flash::error('Other Payroll Deductions not found');

            return redirect(route('otherPayrollDeductions.index'));
        }

        return view('other_payroll_deductions.show')->with('otherPayrollDeductions', $otherPayrollDeductions);
    }

    /**
     * Show the form for editing the specified OtherPayrollDeductions.
     */
    public function edit($id)
    {
        $otherPayrollDeductions = $this->otherPayrollDeductionsRepository->find($id);

        if (empty($otherPayrollDeductions)) {
            Flash::error('Other Payroll Deductions not found');

            return redirect(route('otherPayrollDeductions.index'));
        }

        return view('other_payroll_deductions.edit')->with('otherPayrollDeductions', $otherPayrollDeductions);
    }

    /**
     * Update the specified OtherPayrollDeductions in storage.
     */
    public function update($id, UpdateOtherPayrollDeductionsRequest $request)
    {
        $otherPayrollDeductions = $this->otherPayrollDeductionsRepository->find($id);

        if (empty($otherPayrollDeductions)) {
            Flash::error('Other Payroll Deductions not found');

            return redirect(route('otherPayrollDeductions.index'));
        }

        $otherPayrollDeductions = $this->otherPayrollDeductionsRepository->update($request->all(), $id);

        Flash::success('Other Payroll Deductions updated successfully.');

        return redirect(route('otherPayrollDeductions.index'));
    }

    /**
     * Remove the specified OtherPayrollDeductions from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $otherPayrollDeductions = $this->otherPayrollDeductionsRepository->find($id);

        if (empty($otherPayrollDeductions)) {
            Flash::error('Other Payroll Deductions not found');

            return redirect(route('otherPayrollDeductions.index'));
        }

        $this->otherPayrollDeductionsRepository->delete($id);

        Flash::success('Other Payroll Deductions deleted successfully.');

        return redirect(route('otherPayrollDeductions.index'));
    }

    public function multiplePayrollDeductions(Request $request) {
        return view('/other_payroll_deductions/multiple_payroll_deductions', [

        ]);
    }

    public function getOtherDeductionMultipleData(Request $request) {
        $department = $request['Department'];
        $schedule = $request['Schedule'];

        if ($department == 'All') {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->select(
                    'Employees.id',
                    'FirstName',
                    'MiddleName',
                    'LastName',
                    'Suffix',
                    DB::raw("(SELECT TOP 1 Amount FROM OtherPayrollDeductions WHERE ScheduleDate='" . $schedule . "' AND EmployeeId=Employees.id ORDER BY ScheduleDate DESC) AS Amount"),
                    DB::raw("(SELECT TOP 1 DeductionDescription FROM OtherPayrollDeductions WHERE ScheduleDate='" . $schedule . "' AND EmployeeId=Employees.id ORDER BY ScheduleDate DESC) AS DeductionDescription"),
                )
                ->orderBy('LastName')
                ->get();
        } else {
            if ($department == 'SUB-OFFICE') {
                $employees = DB::table('Employees')
                    ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                    ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                    ->whereRaw("Employees.OfficeDesignation='" . $department . "'")
                    ->select(
                        'Employees.id',
                        'FirstName',
                        'MiddleName',
                        'LastName',
                        'Suffix',
                        DB::raw("(SELECT TOP 1 Amount FROM OtherPayrollDeductions WHERE ScheduleDate='" . $schedule . "' AND EmployeeId=Employees.id ORDER BY ScheduleDate DESC) AS Amount"),
                        DB::raw("(SELECT TOP 1 DeductionDescription FROM OtherPayrollDeductions WHERE ScheduleDate='" . $schedule . "' AND EmployeeId=Employees.id ORDER BY ScheduleDate DESC) AS DeductionDescription"),
                    )
                    ->orderBy('LastName')
                    ->get();
            } else {
                $employees = DB::table('Employees')
                    ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                    ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                    ->whereRaw("Positions.Department='" . $department . "' AND Employees.OfficeDesignation NOT IN ('SUB-OFFICE')")
                    ->select(
                        'Employees.id',
                        'FirstName',
                        'MiddleName',
                        'LastName',
                        'Suffix',
                        DB::raw("(SELECT TOP 1 Amount FROM OtherPayrollDeductions WHERE ScheduleDate='" . $schedule . "' AND EmployeeId=Employees.id ORDER BY ScheduleDate DESC) AS Amount"),
                        DB::raw("(SELECT TOP 1 DeductionDescription FROM OtherPayrollDeductions WHERE ScheduleDate='" . $schedule . "' AND EmployeeId=Employees.id ORDER BY ScheduleDate DESC) AS DeductionDescription"),
                    )
                    ->orderBy('LastName')
                    ->get();
            }            
        }

        return response()->json($employees, 200);
    }

    public function updateOtherDeductionData(Request $request) {
        $employeeId = $request['EmployeeId'];
        $schedule = $request['Schedule'];
        $amount = $request['Amount'];
        $description = $request['Description'];

        // DELETE EXISTING FIRST
        OtherPayrollDeductions::where('EmployeeId', $employeeId)
            ->where('ScheduleDate', $schedule)
            ->delete();

        if ($amount != null) {
            // CREATE NEW
            $others = new OtherPayrollDeductions;
            $others->id = IDGenerator::generateIDandRandString();
            $others->EmployeeId = $employeeId;
            $others->DeductionName = 'AR - Others';
            $others->ScheduleDate = $schedule;
            $others->DeductionDescription = $description;
            $others->Type = 'Others';
            $others->Amount = $amount;

            $others->save();

            UserFootprints::log('Added AR-Others', "Updated AR-Others amount for payroll schedule " . date('F d, Y', strtotime($schedule)) . " amounting to " . $amount);
        }
        return response()->json('ok', 200);
    }

    public function addOnsAndDeductions(Request $request) {
        return view('/other_payroll_deductions/addons_and_deductions', [

        ]);
    }
}
