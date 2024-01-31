<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayrollIndexRequest;
use App\Http\Requests\UpdatePayrollIndexRequest;
use App\Repositories\PayrollIndexRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employees;
use App\Models\PayrollIndex;
use App\Models\LeaveAttendanceDates;
use App\Models\Overtimes;
use App\Models\PayrollDetails;
use App\Models\PayrollSchedules;
use App\Models\Notifications;
use App\Models\User;
use App\Models\Positions;
use App\Models\EmployeesDesignations;
use App\Models\ProfessionalIDs;
use App\Models\AttendanceData;
use Flash;
use Response;

class PayrollIndexController extends AppBaseController
{
    /** @var  PayrollIndexRepository */
    private $payrollIndexRepository;

    public function __construct(PayrollIndexRepository $payrollIndexRepo)
    {
        $this->middleware('auth');
        $this->payrollIndexRepository = $payrollIndexRepo;
    }

    /**
     * Display a listing of the PayrollIndex.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $payrollIndices = $this->payrollIndexRepository->all();

        return view('payroll_indices.index')
            ->with('payrollIndices', $payrollIndices);
    }

    /**
     * Show the form for creating a new PayrollIndex.
     *
     * @return Response
     */
    public function create()
    {
        return view('payroll_indices.create');
    }

    /**
     * Store a newly created PayrollIndex in storage.
     *
     * @param CreatePayrollIndexRequest $request
     *
     * @return Response
     */
    public function store(CreatePayrollIndexRequest $request)
    {
        $input = $request->all();

        $payrollIndex = $this->payrollIndexRepository->create($input);

        Flash::success('Payroll Index saved successfully.');

        return redirect(route('payrollIndices.index'));
    }

    /**
     * Display the specified PayrollIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $payrollIndex = $this->payrollIndexRepository->find($id);

        if (empty($payrollIndex)) {
            Flash::error('Payroll Index not found');

            return redirect(route('payrollIndices.index'));
        }

        return view('payroll_indices.show')->with('payrollIndex', $payrollIndex);
    }

    /**
     * Show the form for editing the specified PayrollIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $payrollIndex = $this->payrollIndexRepository->find($id);

        if (empty($payrollIndex)) {
            Flash::error('Payroll Index not found');

            return redirect(route('payrollIndices.index'));
        }

        return view('payroll_indices.edit')->with('payrollIndex', $payrollIndex);
    }

    /**
     * Update the specified PayrollIndex in storage.
     *
     * @param int $id
     * @param UpdatePayrollIndexRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePayrollIndexRequest $request)
    {
        $payrollIndex = $this->payrollIndexRepository->find($id);

        if (empty($payrollIndex)) {
            Flash::error('Payroll Index not found');

            return redirect(route('payrollIndices.index'));
        }

        $payrollIndex = $this->payrollIndexRepository->update($request->all(), $id);

        Flash::success('Payroll Index updated successfully.');

        return redirect(route('payrollIndices.index'));
    }

    /**
     * Remove the specified PayrollIndex from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $payrollIndex = $this->payrollIndexRepository->find($id);

        if (empty($payrollIndex)) {
            Flash::error('Payroll Index not found');

            return redirect(route('payrollIndices.index'));
        }

        $this->payrollIndexRepository->delete($id);

        Flash::success('Payroll Index deleted successfully.');

        return redirect(route('payrollIndices.index'));
    }

    public function choosePayrollType() {
        $type = DB::table('EmployeesDesignations')
                ->select('Status')
                ->groupBy('Status')
                ->get();

        $departments = DB::table('Positions')
            ->select('Department')
            ->groupBy('Department')
            ->orderBy('Department')
            ->get();

        return view('/payroll_indices/choose_payroll_type', [
            'type' => $type,
            'departments' => $departments
        ]);
    }

    public function validatePayrollSelectType(Request $request) {
        set_time_limit(720);

        $type = $request['status'];

        $payrollIndex = PayrollIndex::where('EmployeeType', $type)
            ->where('Department', $request['Department'])
            ->where('DateFrom', $request['DateFrom'])
            ->where('DateTo', $request['DateTo'])
            ->first();

        if ($payrollIndex != null) {
            $payrollIndex->DateFrom = $request['DateFrom'];
            $payrollIndex->DateTo = $request['DateTo'];
            $payrollIndex->EmployeeType = $type;
            $payrollIndex->SalaryPeriod = $request['SalaryPeriod'];
            $payrollIndex->Department = $request['Department'];
            $payrollIndex->save();
            return redirect(route('payrollIndices.process-payroll', [$payrollIndex->id]));
        } else {
            $payrollIndex = new PayrollIndex;
            $payrollIndex->DateFrom = $request['DateFrom'];
            $payrollIndex->DateTo = $request['DateTo'];
            $payrollIndex->EmployeeType = $type;
            $payrollIndex->SalaryPeriod = $request['SalaryPeriod'];
            $payrollIndex->Department = $request['Department'];
            $payrollIndex->save();

            return redirect(route('payrollIndices.process-payroll', [$payrollIndex->id]));
        }
    }

    public function processPayroll($payrollIndexId) {
        set_time_limit(720);
        
        $payrollIndex = PayrollIndex::find($payrollIndexId);

        $noOfDays = abs(round((strtotime($payrollIndex->DateTo . ' +1 day') - strtotime($payrollIndex->DateFrom)) / 86400));

        $employees = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.FirstName',
                    'Employees.MiddleName',
                    'Employees.LastName',
                    'Employees.Suffix',
                    'Employees.id',
                    'Employees.BiometricsUserId',
                    'Employees.PayrollScheduleId',
                    'Positions.BasicSalary AS SalaryAmount',
                    'EmployeesDesignations.Status',
                    )
            ->where('EmployeesDesignations.Status', $payrollIndex->EmployeeType)
            ->where('Positions.Department', $payrollIndex->Department)
            ->orderBy('Employees.LastName')
            ->get();

        $arr = [];

        foreach($employees as $item) {

            $baseSalary = floatval($item->SalaryAmount);
            $halfSalary = round($baseSalary/2, 2);
            $salaryPerHour = round(round(($baseSalary*12)/302, 2)/8, 2); // 22 working days a month, 8 hours a day

            $attArr = [];
            $totalLateMinutes = 0;
            for ($i = 0; $i < $noOfDays; $i++) {
                $currentDate = date('Y-m-d', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'));
                $day = date('D', strtotime($currentDate));
                $attendance = DB::table('PayrollSchedules')
                    ->select(
                        DB::raw("(SELECT TOP 1 Timestamp FROM AttendanceData WHERE BiometricUserId = '" . $item->BiometricsUserId . "' AND (Timestamp BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(Timestamp as DATETIME) < DATEADD(hh, 2, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.StartTime AS TIME(0))) AS DATETIME)))) AS 'MorningIn'"),
                        DB::raw("(SELECT TOP 1 AbsentPermission FROM AttendanceData WHERE BiometricUserId = '" . $item->BiometricsUserId . "' AND (Timestamp BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(Timestamp as DATETIME) < DATEADD(hh, 2, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.StartTime AS TIME(0))) AS DATETIME)))) AS 'MorningLeave'"),
                        DB::raw("DATEADD(minute, 6, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.StartTime AS TIME(0))) AS DATETIME)) AS MorningTimeInEnd"),
                        DB::raw("(SELECT TOP 1 Timestamp FROM AttendanceData WHERE BiometricUserId = '" . $item->BiometricsUserId . "' AND (Timestamp BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(Timestamp as DATETIME) BETWEEN DATEADD(hh, 2, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.StartTime AS TIME(0))) AS DATETIME)) AND DATEADD(minute, 30, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.BreakStart AS TIME(0))) AS DATETIME)))) AS 'MorningOut'"),
                        DB::raw("CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.BreakStart AS TIME(0))) AS MorningTimeOutStart"),
                        DB::raw("(SELECT TOP 1 Timestamp FROM AttendanceData WHERE BiometricUserId = '" . $item->BiometricsUserId . "' AND (Timestamp BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(Timestamp as DATETIME) BETWEEN DATEADD(minute, 30, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.BreakStart AS TIME(0))) AS DATETIME)) AND DATEADD(hh, 2, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.BreakEnd AS TIME(0))) AS DATETIME)))) AS 'AfternoonIn'"),
                        DB::raw("(SELECT TOP 1 AbsentPermission FROM AttendanceData WHERE BiometricUserId = '" . $item->BiometricsUserId . "' AND (Timestamp BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(Timestamp as DATETIME) BETWEEN DATEADD(minute, 30, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.BreakStart AS TIME(0))) AS DATETIME)) AND DATEADD(hh, 2, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.BreakEnd AS TIME(0))) AS DATETIME)))) AS 'AfternoonLeave'"),
                        DB::raw("DATEADD(minute, 6, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.BreakEnd AS TIME(0))) AS DATETIME)) AS AfternoonTimeInEnd"),
                        DB::raw("(SELECT TOP 1 Timestamp FROM AttendanceData WHERE BiometricUserId = '" . $item->BiometricsUserId . "' AND (Timestamp BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(Timestamp as DATETIME) > DATEADD(hh, 2, TRY_CAST(CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.BreakEnd AS TIME(0))) AS DATETIME)))) AS 'AfternoonOut'"),
                        DB::raw("CONCAT('" . $currentDate . " ', TRY_CAST(PayrollSchedules.EndTime AS TIME(0))) AS AfternoonTimeOutStart"),
                    )
                    ->whereRaw("id='" . $item->PayrollScheduleId . "'")
                    ->first();

                $leaveAttDate = LeaveAttendanceDates::where('EmployeeId', $item->id)
                    ->where('DateOfLeave', $currentDate)
                    ->first();

                $ot = Overtimes::where('DateOfOT', $currentDate)
                    ->where('EmployeeId', $item->id)
                    ->first();
                $otTotal = 0;
                $otMultiplier = 0;
                if ($ot != null) {
                    $otTotal = abs(round((strtotime(date('H:i:s', strtotime($ot->To)) . '') - strtotime(date('H:i:s', strtotime($ot->From)) . ''))/3600, 1));
                    $otMultiplier = floatval($ot->Multiplier);
                }

                /**
                 * FILTER IF NO TIME OUT AND TIME IN DURING LUNCH
                 */
                if ($attendance->MorningOut == null && $attendance->AfternoonOut != null) {
                    $attendance->MorningOut = date('Y-m-d H:i:s', strtotime($currentDate . ' 12:01:00'));
                }

                if ($attendance->AfternoonIn == null && $attendance->AfternoonOut != null) {
                    $attendance->AfternoonIn = date('Y-m-d H:i:s', strtotime($currentDate . ' 12:35:00'));
                }

                $amHours = $leaveAttDate==null ? 
                            (
                                $attendance != null ?
                                    (PayrollIndex::validateMorningHours($attendance) ? PayrollIndex::getTotalHours(
                                            strtotime(date('H:i:s', strtotime($attendance->MorningIn))), 
                                            strtotime(date('H:i:s', strtotime($attendance->MorningOut))), 
                                            strtotime(date('H:i:s', strtotime($attendance->MorningTimeInEnd))), 
                                            strtotime(date('H:i:s', strtotime($attendance->MorningTimeOutStart)))
                                        ) : 0) : 0
                            ) : 4;
                $pmHours = $leaveAttDate==null ? 
                            (
                                $attendance != null ?
                                    (PayrollIndex::validateAfternoonHours($attendance) ? PayrollIndex::getTotalHours(
                                            strtotime(date('H:i:s', strtotime($attendance->AfternoonIn))), 
                                            strtotime(date('H:i:s', strtotime($attendance->AfternoonOut))), 
                                            strtotime(date('H:i:s', strtotime($attendance->AfternoonTimeInEnd))), 
                                            strtotime(date('H:i:s', strtotime($attendance->AfternoonTimeOutStart)))
                                        ) : 0) : 0
                            ) : 4;

                $amLate = $attendance != null ? (PayrollIndex::validateMorningHours($attendance) ? 
                    PayrollIndex::getLateMinutes(strtotime(date('H:i:s', strtotime($attendance->MorningIn))), 
                            strtotime(date('H:i:s', strtotime($attendance->MorningOut))), 
                            strtotime(date('H:i:s', strtotime($attendance->MorningTimeInEnd))), 
                            strtotime(date('H:i:s', strtotime($attendance->MorningTimeOutStart)))
                        ) : 240) : 240; // 240 MINUTES = 4 HOURS if there is no attendance

                $pmLate = $attendance != null ? (PayrollIndex::validateAfternoonHours($attendance) ? 
                    PayrollIndex::getLateMinutes(
                                    strtotime(date('H:i:s', strtotime($attendance->AfternoonIn))), 
                                    strtotime(date('H:i:s', strtotime($attendance->AfternoonOut))), 
                                    strtotime(date('H:i:s', strtotime($attendance->AfternoonTimeInEnd))), 
                                    strtotime(date('H:i:s', strtotime($attendance->AfternoonTimeOutStart)))
                                ) : 240) : 240; // 240 MINUTES = 4 HOURS if there is no attendance

                // GET TOTAL MINUTES LATE
                if ($day=='Sat' || $day=='Sun') {

                } else {
                    $totalLateMinutes += ($amLate + $pmLate);
                }   

                array_push($attArr, [
                        'Date' => $currentDate,
                        'AM' => $amHours,
                        'PM' => $pmHours,
                        'AMTotal' => $amHours * $salaryPerHour,
                        'PMTotal' => $pmHours * $salaryPerHour,
                        'OT' => $otTotal,
                        'OTTotal' => $otTotal * $otMultiplier * $salaryPerHour,
                        'AMLeave' => $attendance->MorningLeave,
                        'PMLeave' => $attendance->AfternoonLeave,
                        'AMMinutesLate' => $amLate,
                        'PMMinutesLate' => $pmLate,
                    ]);
            }

            $deductions = DB::table('ProfessionalIDS')
                ->select('Entity', 'EntityId', 'ContributionAmount')
                ->where('EmployeeId', $item->id)
                ->whereIn('Entity', ['Pag-Ibig', 'PhilHealth', 'SSS', 'TIN'])
                ->groupByRaw('Entity, EntityId, ContributionAmount')
                ->get();
            
            $sss = 0.0;
            $pagIbig = 0.0;
            $philHealth = 0.0;
            $tax = 0.0;
            $totalDeductions = 0.0;

            foreach($deductions as $deduction) {
                if ($deduction->Entity=='SSS') {
                    $sss = floatval($deduction->ContributionAmount);
                    $totalDeductions += $sss;
                } elseif($deduction->Entity=='Pag-Ibig') {
                    $pagIbig = floatval($deduction->ContributionAmount);
                    $totalDeductions += $pagIbig;
                } elseif($deduction->Entity=='PhilHealth') {
                    $philHealth = floatval($deduction->ContributionAmount);
                    $totalDeductions += $philHealth;
                } elseif($deduction->Entity=='TIN') {
                    $tax = floatval($deduction->ContributionAmount);
                }
            }

            array_push($arr, [
                'EmployeeId' => $item->id,
                'Employee' => Employees::getMergeName($item),
                'SalaryPerHour' => $salaryPerHour,
                'BaseSalary' => $baseSalary,
                'SalaryAddOns' => 0,
                'Data' => $attArr,
                'SSS' => $sss,
                'Pag-Ibig' => $pagIbig,
                'PhilHealth' => $philHealth,
                'Tax' => $tax,
                'TotalDeductions' => $totalDeductions,
                'TotalMinutesLate' => $totalLateMinutes,
                'TotalLateDeduction' => round(($salaryPerHour/60) * $totalLateMinutes, 2),
            ]);
        }

        return view('/payroll_indices/process_payroll', [
            'data' => $arr,
            'payrollIndex' => $payrollIndex,
        ]);
    }

    public function generatePayroll($id) {
        $payrollIndex = PayrollIndex::find($id);

        $noOfDays = abs(round((strtotime($payrollIndex->DateTo . ' +1 day') - strtotime($payrollIndex->DateFrom)) / 86400));

        $employees = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.id', '=', 'EmployeesDesignations.EmployeeId')
            ->select('Employees.FirstName',
                    'Employees.MiddleName',
                    'Employees.LastName',
                    'Employees.Suffix',
                    'Employees.id',
                    'EmployeesDesignations.SalaryAmount',
                    'EmployeesDesignations.SalaryAddOns',
                    'EmployeesDesignations.Status',)
            ->where('EmployeesDesignations.IsActive', 'Yes')
            ->where('EmployeesDesignations.Status', $payrollIndex->EmployeeType)
            ->orderBy('Employees.FirstName')
            ->get();

        $arr = [];

        foreach($employees as $item) {

            $baseSalary = floatval($item->SalaryAmount);
            $salaryPerHour = $baseSalary/22/8; // 22 working days a month, 8 hours a day

            $grossAmount = 0;

            // $attArr = [];
            for ($i = 0; $i < $noOfDays; $i++) {
                $currentDate = date('Y-m-d', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'));
                $attendance = DB::table('AttendanceRules')
                    ->select(
                        DB::raw("(SELECT TOP 1 LogTime FROM Attendances WHERE EmployeeId = '" . $item->id . "' AND (LogTime BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(LogTime as TIME) BETWEEN AttendanceRules.MorningTimeInStart AND AttendanceRules.MorningAbsentThreshold)) AS 'MorningIn'"),
                        'AttendanceRules.MorningTimeInEnd',
                        DB::raw("(SELECT TOP 1 LogTime FROM Attendances WHERE EmployeeId = '" . $item->id . "' AND (LogTime BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(LogTime as TIME) BETWEEN AttendanceRules.MorningUndertimeThreshold AND AttendanceRules.MorningTimeOutEnd)) AS 'MorningOut'"),
                        'AttendanceRules.MorningTimeOutStart',
                        DB::raw("(SELECT TOP 1 LogTime FROM Attendances WHERE EmployeeId = '" . $item->id . "' AND (LogTime BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(LogTime as TIME) BETWEEN AttendanceRules.AfternoonTimeInStart AND AttendanceRules.AfternoonAbsentThreshold)) AS 'AfternoonIn'"),
                        'AttendanceRules.AfternoonTimeInEnd',
                        DB::raw("(SELECT TOP 1 LogTime FROM Attendances WHERE EmployeeId = '" . $item->id . "' AND (LogTime BETWEEN '" . $currentDate . "' AND '" . date('Y-m-d', strtotime($currentDate . ' +1 day')) . "') AND (CAST(LogTime as TIME) BETWEEN AttendanceRules.AfternoonUndertimeThreshold AND AttendanceRules.AfternoonTimeOutEnd)) AS 'AfternoonOut'"),
                        'AttendanceRules.AfternoonTimeOutStart'
                    )->first();

                $leaveAttDate = LeaveAttendanceDates::where('EmployeeId', $item->id)
                    ->where('DateOfLeave', $currentDate)
                    ->first();

                $ot = Overtimes::where('DateOfOT', $currentDate)
                    ->where('EmployeeId', $item->id)
                    ->first();
                $otTotal = 0;
                $otMultiplier = 0;
                if ($ot != null) {
                    $otTotal = abs(round((strtotime(date('H:i:s', strtotime($ot->To)) . '') - strtotime(date('H:i:s', strtotime($ot->From)) . ''))/3600, 1));
                    $otMultiplier = floatval($ot->Multiplier);
                }

                $amHours = $leaveAttDate==null ? (PayrollIndex::validateMorningHours($attendance) ? PayrollIndex::getTotalHours(strtotime(date('H:i:s', strtotime($attendance->MorningIn)) . ''), strtotime(date('H:i:s', strtotime($attendance->MorningOut)) . ''), strtotime(date('H:i:s', strtotime($currentDate . ' ' . $attendance->MorningTimeInEnd)) . ''), strtotime(date('H:i:s', strtotime($currentDate . ' ' . $attendance->MorningTimeOutStart)) . '')) : 0) : 4;
                $pmHours = $leaveAttDate==null ? (PayrollIndex::validateAfternoonHours($attendance) ? PayrollIndex::getTotalHours(strtotime(date('H:i:s', strtotime($attendance->AfternoonIn)) . ''), strtotime(date('H:i:s', strtotime($attendance->AfternoonOut)) . ''), strtotime(date('H:i:s', strtotime($currentDate . ' ' . $attendance->AfternoonTimeInEnd)) . ''), strtotime(date('H:i:s', strtotime($currentDate . ' ' . $attendance->AfternoonTimeOutStart)) . '')) : 0) : 4;

                $grossAmount += ($amHours * $salaryPerHour) + ($pmHours * $salaryPerHour) + ($otTotal * $otMultiplier * $salaryPerHour);
            }

            $deductions = DB::table('ProfessionalIDS')
                ->select('Entity', 'EntityId', 'ContributionAmount')
                ->where('EmployeeId', $item->id)
                ->whereIn('Entity', ['Pag-Ibig', 'PhilHealth', 'SSS', 'TIN'])
                ->groupByRaw('Entity, EntityId, ContributionAmount')
                ->get();
            
            $sss = 0.0;
            $pagIbig = 0.0;
            $philHealth = 0.0;
            $tax = 0.0;
            $totalDeductions = 0.0;

            foreach($deductions as $deduction) {
                if ($deduction->Entity=='SSS') {
                    $sss = floatval($deduction->ContributionAmount);
                    $totalDeductions += $sss;
                } elseif($deduction->Entity=='Pag-Ibig') {
                    $pagIbig = floatval($deduction->ContributionAmount);
                    $totalDeductions += $pagIbig;
                } elseif($deduction->Entity=='PhilHealth') {
                    $philHealth = floatval($deduction->ContributionAmount);
                    $totalDeductions += $philHealth;
                } elseif($deduction->Entity=='TIN') {
                    $tax = floatval($deduction->ContributionAmount);
                }
            }

            $netPay = ($grossAmount-$totalDeductions) + floatval($item->SalaryAddOns) - $tax;

            $payrollDetails = new PayrollDetails;
            $payrollDetails->PayrolIndexId = $id;
            $payrollDetails->EmployeeId = $item->id;
            $payrollDetails->GrossSalary = $grossAmount;
            $payrollDetails->TotalDeductions = $totalDeductions;
            $payrollDetails->AddOns = number_format(floatval($item->SalaryAddOns), 2);
            $payrollDetails->Vat = $tax;
            $payrollDetails->NetSalary = $netPay;
            $payrollDetails->save();

            // ADD NOTIFICATIONS
            $userFromEmployee = User::where('employee_id', $item->id)->first();
            if ($userFromEmployee != null) {
                $notifications = new Notifications;
                $notifications->UserId = $userFromEmployee->id;
                $notifications->Type = 'PAYROLL_INFO';
                $notifications->Content = "Your payslip (payroll) for " . date('F d, Y', strtotime($payrollIndex->DateFrom)) . " to " . date('F d, Y', strtotime($payrollIndex->DateTo)) . " is ready.";
                $notifications->Notes = $payrollDetails->id;
                $notifications->Status = "UNREAD";
                $notifications->save();
            }
        }

        return redirect(route('payrollIndices.show', [$id]));
    }

    public function payslip($id) {
        $payrollDetails = PayrollDetails::find($id);
        $payrollIndex = PayrollIndex::find($payrollDetails->PayrolIndexId);
        $employee = Employees::find($payrollDetails->EmployeeId);
        $employeeDesignation = EmployeesDesignations::where('EmployeeId', $employee->id)
            ->where('IsActive', 'Yes')
            ->first();
        $deductions = ProfessionalIDs::where('EmployeeId', $employee->id)->get();

        return view('/payroll_indices/payslip', [
            'payrollDetails' => $payrollDetails,
            'payrollIndex' => $payrollIndex,
            'employee' => $employee,
            'employeeDesignation' => $employeeDesignation,
            'deductions' => $deductions,
        ]);
    }

    public function payroll(Request $request) {
        return view('/payroll_indices/payroll', [

        ]);
    }

    public function getPayrollData(Request $request) {
        $employeeType = $request['EmployeeType'];
        $department = $request['Department'];
        $salaryPeriod = $request['SalaryPeriod'];
        $from = $request['From'];
        $to = $request['To'];

        $employees = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->leftJoin('PayrollSchedules', 'Employees.PayrollScheduleId', '=', 'PayrollSchedules.id')
            ->select('Employees.FirstName',
                    'Employees.MiddleName',
                    'Employees.LastName',
                    'Employees.Suffix',
                    'Employees.id',
                    'Employees.BiometricsUserId',
                    'Employees.PayrollScheduleId',
                    'Employees.NoAttendanceAllowed',
                    'Positions.BasicSalary AS SalaryAmount',
                    'EmployeesDesignations.Status',
                    'PayrollSchedules.StartTime',
                    'PayrollSchedules.BreakStart',
                    'PayrollSchedules.BreakEnd',
                    'PayrollSchedules.EndTime',
            )
            ->where('EmployeesDesignations.Status', $employeeType)
            ->where('Positions.Department', $department)
            ->orderBy('Employees.LastName')
            ->get();

        $defaultSched = PayrollSchedules::where('Name', 'Default')->orderByDesc('created_at')->first();

        foreach($employees as $item) {
            $item->AttendanceData = DB::table('AttendanceData')
                ->whereRaw("BiometricUserId='" . $item->BiometricsUserId . "' AND (TRY_CAST(Timestamp AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND AbsentPermission IS NULL")
                ->select(
                    DB::raw("TRY_CAST(Timestamp AS DATE) AS DateLogged"), 
                    'Timestamp', 
                    'Type', 
                    'AbsentPermission')
                ->orderBy('Timestamp')
                ->get();
        }

        return response()->json($employees, 200);
    }
}
