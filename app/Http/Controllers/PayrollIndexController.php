<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayrollIndexRequest;
use App\Http\Requests\UpdatePayrollIndexRequest;
use App\Repositories\PayrollIndexRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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
use App\Models\HolidaysList;
use App\Models\PayrollExpandedDetails;
use App\Models\UserFootprints;
use App\Models\LoanDetails;
use App\Models\AttachedAccounts;
use App\Models\PayrollBillsAttachments;
use App\Models\PaidBills;
use App\Models\Bills;
use App\Models\EmployeeDayOffs;
use App\Exports\FCBUploadTemplate;
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
        $data = DB::table('PayrollExpandedDetails')
            ->select(
                'SalaryPeriod',
                DB::raw("SUM(NetPay) AS NetPay"),
                DB::raw("COUNT(id) AS TotalCount"),
                DB::raw("TRY_CAST(GeneratedDate AS DATE) AS GeneratedDate"),
                DB::raw("(SELECT TOP 1 x.Status FROM PayrollExpandedDetails x WHERE x.SalaryPeriod=PayrollExpandedDetails.SalaryPeriod ORDER BY x.updated_at DESC) AS Status")
            )
            ->groupByRaw("TRY_CAST(GeneratedDate AS DATE)")
            ->groupBy('SalaryPeriod')
            ->orderByRaw("TRY_CAST(GeneratedDate AS DATE) DESC")
            ->get();

        return view('/payroll_indices/index', [
            'data' => $data,
        ]);
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

        // period month
        if (date('d', strtotime($salaryPeriod)) == '15') {
            $loanPeriodMonth = date('Y-m-15', strtotime($salaryPeriod));
        } else {
            $loanPeriodMonth = date('Y-m-d', strtotime('last day of ' . $salaryPeriod));
        }

        $checkPayrollData = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $loanPeriodMonth . "' AND Department='" . $department . "' AND EmployeeType='" . $employeeType . "'")
            ->groupBy('Status')
            ->select('Status')
            ->first();

        if ($department == 'SUB-OFFICE') {
            $employees = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->leftJoin('PayrollSchedules', 'Employees.PayrollScheduleId', '=', 'PayrollSchedules.id')
                ->leftJoin('EmployeePayrollSundries', 'Employees.id', '=', 'EmployeePayrollSundries.EmployeeId')
                ->select('Employees.FirstName',
                        'Employees.MiddleName',
                        'Employees.LastName',
                        'Employees.Suffix',
                        'Employees.id',
                        'Employees.BiometricsUserId',
                        'Employees.PayrollScheduleId',
                        'Employees.NoAttendanceAllowed',
                        'Employees.DayOffDates',
                        'Positions.BasicSalary AS SalaryAmount',
                        'Positions.Level',
                        'EmployeesDesignations.Status',
                        'PayrollSchedules.StartTime',
                        'PayrollSchedules.BreakStart',
                        'PayrollSchedules.BreakEnd',
                        'PayrollSchedules.EndTime',
                        'EmployeePayrollSundries.Longevity',
                        'EmployeePayrollSundries.RiceAllowance',
                        'EmployeePayrollSundries.Insurances',
                        'EmployeePayrollSundries.PagIbigContribution',
                        'EmployeePayrollSundries.SSSContribution',
                        'EmployeePayrollSundries.PhilHealth',
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
                ->leftJoin('PayrollSchedules', 'Employees.PayrollScheduleId', '=', 'PayrollSchedules.id')
                ->leftJoin('EmployeePayrollSundries', 'Employees.id', '=', 'EmployeePayrollSundries.EmployeeId')
                ->select('Employees.FirstName',
                        'Employees.MiddleName',
                        'Employees.LastName',
                        'Employees.Suffix',
                        'Employees.id',
                        'Employees.BiometricsUserId',
                        'Employees.PayrollScheduleId',
                        'Employees.NoAttendanceAllowed',
                        'Employees.DayOffDates',
                        'Positions.BasicSalary AS SalaryAmount',
                        'Positions.Level',
                        'EmployeesDesignations.Status',
                        'PayrollSchedules.StartTime',
                        'PayrollSchedules.BreakStart',
                        'PayrollSchedules.BreakEnd',
                        'PayrollSchedules.EndTime',
                        'EmployeePayrollSundries.Longevity',
                        'EmployeePayrollSundries.RiceAllowance',
                        'EmployeePayrollSundries.Insurances',
                        'EmployeePayrollSundries.PagIbigContribution',
                        'EmployeePayrollSundries.SSSContribution',
                        'EmployeePayrollSundries.PhilHealth',
                )
                ->where('EmployeesDesignations.Status', $employeeType)
                ->where('Positions.Department', $department)
                ->whereRaw("Employees.OfficeDesignation NOT IN ('SUB-OFFICE') AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->orderBy('Employees.LastName')
                ->get();
        }

        $defaultSched = PayrollSchedules::where('Name', 'Default')->orderByDesc('created_at')->first();

        $specialDutyDays = DB::table('SpecialDutyDays')
            ->whereRaw("Date BETWEEN '" . $from . "' AND '" . $to . "'")
            ->select('Date', 'Term')
            ->get();

        foreach($employees as $item) {
            // ADD ATTENDANCE
            $item->AttendanceData = DB::table('AttendanceData')
                ->whereRaw("BiometricUserId='" . $item->BiometricsUserId . "' AND (TRY_CAST(Timestamp AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND AbsentPermission IS NULL")
                ->select(
                    DB::raw("TRY_CAST(Timestamp AS DATE) AS DateLogged"), 
                    'Timestamp', 
                    'Type', 
                    'AbsentPermission')
                ->orderBy('Timestamp')
                ->get();

            // ADD SPECIAL DUTY DAYS
            $item->SpecialDutyDays = $specialDutyDays;

            // LEAVE DAYS
            $item->LeaveDays = DB::table('LeaveDays')
                ->leftJoin('LeaveApplications', 'LeaveDays.LeaveId', '=', 'LeaveApplications.id')
                ->whereRaw("LeaveApplications.EmployeeId='" . $item->id . "' AND LeaveApplications.Status='APPROVED' AND 
                    (LeaveDays.LeaveDate BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'LeaveDays.LeaveDate',
                    'LeaveDays.Duration'
                )
                ->groupBy('LeaveDays.LeaveDate', 'LeaveDays.Duration')
                ->orderBy('LeaveDays.LeaveDate')
                ->get();

            // OFFSETS
            $item->Offsets = DB::table('OffsetApplications')
                ->whereRaw("EmployeeId='" . $item->id . "' AND Status='APPROVED' AND (DateOfOffset BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select('DateOfOffset')
                ->get();

            $item->TripTickets = DB::table('TripTicketPassengers')
                ->leftJoin('TripTickets', 'TripTicketPassengers.TripTicketId', '=', 'TripTickets.id')
                ->whereRaw("TripTicketPassengers.EmployeeId='" . $item->id . "' AND TripTickets.Status='APPROVED' AND (TripTickets.DateOfTravel BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select('DateOfTravel')
                ->get();

            $item->Overtimes = DB::table('Overtimes')
                ->whereRaw("EmployeeId='" . $item->id . "' AND Status='APPROVED' AND (DateOfOT BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select('Overtimes.*')
                ->get();

            $item->Loans = DB::table('LoanDetails')
                    ->leftJoin('Loans', 'LoanDetails.LoanId', '=', 'Loans.id')
                    ->whereRaw("LoanDetails.Month='" . $loanPeriodMonth . "' AND Loans.EmployeeId='" . $item->id . "'")
                    ->select(
                        'Loans.LoanFor',
                        'Loans.PaymentTerm',
                        'LoanDetails.Month',
                        'LoanDetails.MonthlyAmmortization',
                    )
                    ->get();

            // AR OTHERS
            $item->OtherDeductions = DB::table('OtherPayrollDeductions')
                    ->whereRaw("ScheduleDate='" . $loanPeriodMonth . "' AND EmployeeId='" . $item->id . "' AND Type='Others'")
                    ->select(
                        'Amount',
                    )
                    ->get();

            $item->OtherAddonsAndDeductions = DB::table('OtherAddonsDeductions')
                    ->whereRaw("ScheduleDate='" . $loanPeriodMonth . "' AND EmployeeId='" . $item->id . "'")
                    ->select(
                        'DeductionAmount',
                        'AddonAmount'
                    )
                    ->get();
            $item->ProjectedIncentives = DB::table('EmployeeIncentiveAnnualProjections')
                    ->whereRaw("EmployeeId='" . $item->id . "' AND Year='" . date('Y') . "'")
                    ->get();

            // BOHECO I Bills
            $linkedAccounts = AttachedAccounts::where("EmployeeId", $item->id)->get();
            $totalBillAmount = [];
            foreach($linkedAccounts as $accts) {
                $bills =  DB::connection('sqlsrv_billing')
                    ->table('Bills')
                    ->leftJoin('AccountMaster', 'Bills.AccountNumber', '=', 'AccountMaster.AccountNumber')
                    ->leftJoin('BillsExtension', function($join) {
                        $join->on('Bills.AccountNumber', '=', 'BillsExtension.AccountNumber')
                            ->on('Bills.ServicePeriodEnd', '=', 'BillsExtension.ServicePeriodEnd');
                    })
                    ->whereRaw("Bills.AccountNumber='" . $accts->AccountNumber . "' AND Bills.AccountNumber NOT IN (SELECT AccountNumber FROM PaidBills WHERE AccountNumber=Bills.AccountNumber AND ServicePeriodEnd=Bills.ServicePeriodEnd)")
                    ->select('AccountMaster.ConsumerName',
                            'AccountMaster.ConsumerAddress',
                            'AccountMaster.AccountStatus',
                            'AccountMaster.ComputeMode',
                            'Bills.AccountNumber',
                            'Bills.PowerPreviousReading',
                            'Bills.PowerPresentReading',
                            'Bills.DemandPreviousReading',
                            'Bills.DemandPresentReading',
                            'Bills.NetMeteringNetAmount',
                            'Bills.ReferenceNo',
                            'Bills.DAA_GRAM',
                            'Bills.DAA_ICERA',
                            'Bills.ACRM_TAFPPCA',
                            'Bills.ACRM_TAFxA',
                            'Bills.DAA_VAT',
                            'Bills.ACRM_VAT',
                            'Bills.NetPresReading',
                            'Bills.NetPowerKWH',
                            'Bills.NetGenerationAmount',
                            'Bills.CreditKWH',
                            'Bills.CreditAmount',
                            'Bills.NetMeteringSystemAmt',
                            'Bills.Item3',
                            'Bills.Item4',
                            'Bills.SeniorCitizenDiscount',
                            'Bills.SeniorCitizenSubsidy',
                            'Bills.UCMERefund',
                            'Bills.NetPrevReading',
                            'Bills.CrossSubsidyCreditAmt',
                            'Bills.MissionaryElectrificationAmt',
                            'Bills.EnvironmentalAmt',
                            'Bills.LifelineSubsidyAmt',
                            'Bills.Item1',
                            'Bills.Item2',
                            'Bills.DistributionSystemAmt',
                            'Bills.SupplyRetailCustomerAmt',
                            'Bills.SupplySystemAmt',
                            'Bills.MeteringRetailCustomerAmt',
                            'Bills.MeteringSystemAmt',
                            'Bills.SystemLossAmt',
                            'Bills.FBHCAmt',
                            'Bills.FPCAAdjustmentAmt',
                            'Bills.ForexAdjustmentAmt',
                            'Bills.TransmissionDemandAmt',
                            'Bills.TransmissionSystemAmt',
                            'Bills.DistributionDemandAmt',
                            'Bills.EPAmount',
                            'Bills.PCAmount',
                            'Bills.LoanCondonation',
                            'Bills.BillingPeriod',
                            'Bills.UnbundledTag',
                            'Bills.GenerationSystemAmt',
                            'Bills.PPCAAmount',
                            'Bills.UCAmount',
                            'Bills.MeterNumber',
                            'Bills.ConsumerType',
                            'Bills.BillType',
                            'Bills.QCAmount',
                            'Bills.PPA',
                            'Bills.PPAAmount',
                            'Bills.BasicAmount',
                            'Bills.PRADiscount',
                            'Bills.PRAAmount',
                            'Bills.PPCADiscount',
                            'Bills.AverageKWDemand',
                            'Bills.CoreLoss',
                            'Bills.Meter',
                            'Bills.PR',
                            'Bills.SDW',
                            'Bills.Others',
                            'Bills.ServiceDateFrom',
                            'Bills.ServiceDateTo',
                            'Bills.ServicePeriodEnd',
                            'Bills.DueDate',
                            'Bills.BillNumber',
                            'Bills.Remarks',
                            'Bills.AverageKWH',
                            'Bills.Charges',
                            'Bills.Deductions',
                            'Bills.NetAmount',
                            'Bills.PowerRate',
                            'Bills.DemandRate',
                            'Bills.BillingDate',
                            'Bills.AdditionalKWH',
                            'Bills.AdditionalKWDemand',
                            'Bills.PowerKWH',
                            'Bills.KWHAmount',
                            'Bills.DemandKW',
                            'Bills.KWAmount',
                            'BillsExtension.GenerationVAT',
                            'BillsExtension.TransmissionVAT',
                            'BillsExtension.SLVAT',
                            'BillsExtension.DistributionVAT',
                            'BillsExtension.OthersVAT',
                            'BillsExtension.Item5',
                            'BillsExtension.Item6',
                            'BillsExtension.Item7',
                            'BillsExtension.Item8',
                            'BillsExtension.Item9',
                            'BillsExtension.Item10',
                            'BillsExtension.Item11',
                            'BillsExtension.Item12',
                            'BillsExtension.Item13',
                            'BillsExtension.Item14',
                            'BillsExtension.Item15',
                            'BillsExtension.Item16',
                            'BillsExtension.Item17',
                            'BillsExtension.Item18',
                            'BillsExtension.Item19',
                            'BillsExtension.Item20',
                            'BillsExtension.Item21',
                            'BillsExtension.Item22',
                            'BillsExtension.Item23',
                            'BillsExtension.Item24',)
                        ->get();

                if ($bills != null && count($bills) > 0) {
                    foreach($bills as $bill) {
                        $surcharges = PayrollIndex::getSurcharge($bill);
                        if ($bill->ComputeMode == 'NetMetered') {
                            $billAmnt = round(floatval($bill->NetMeteringNetAmount), 2);
                        } else {
                            $billAmnt = round(floatval($bill->NetAmount), 2);
                        }
                        $netAmnt = $surcharges + $billAmnt;
                        array_push($totalBillAmount, [
                            'AccountNumber' => $bill->AccountNumber,
                            'ServicePeriodEnd' => $bill->ServicePeriodEnd,
                            'BillAmount' => $billAmnt,
                            'Surcharges' => $surcharges,
                            'NetAmount' => $netAmnt,
                        ]);
                    }
                }
            }

            // BEMPC
            $item->BEMPC = DB::table('BEMPC')
                ->select('Amount')
                ->whereRaw("EmployeeId='" . $item->id . "' AND DeductionFor LIKE '%Payroll%' AND DeductionSchedule='" . $salaryPeriod . "'")
                ->get();

            $item->PowerBills = $totalBillAmount;

            // DAY OFFST
            $item->DayOffs = DB::table('EmployeeDayOffs')
                ->whereRaw("EmployeeDayOffs.EmployeeId='" . $item->id . "' AND (EmployeeDayOffs.DayOff BETWEEN '" . $from . "' AND '" . $to . "')")
                ->get();
        }

        $holidays = DB::table('HolidaysList')
            ->whereRaw("(HolidayDate BETWEEN '" . $from . "' AND '" . $to . "')")
            ->select('HolidayDate', 'Holiday')
            ->get();

        $dataSets = [
            'Employees' => $employees,
            'Holidays' => $holidays,
            'CheckPayroll' => $checkPayrollData != null ? [ 'Exists' => 'true', 'Status' => $checkPayrollData->Status ] : [ 'Exists' => 'false', 'Status' => null ],            
        ];

        return response()->json($dataSets, 200);
    }

    public function getPayrollDateInformation(Request $request) {
        $employeeId = $request['EmployeeId'];
        $date = $request['Date'];

        $employee = Employees::find($employeeId);
        if ($employee != null) {
            // GET ATTENDANCE DATA
            $employee->AttendanceData = DB::table('AttendanceData')
                ->whereRaw("BiometricUserId='" . $employee->BiometricsUserId . "' AND (TRY_CAST(Timestamp AS DATE))='" . $date . "' AND AbsentPermission IS NULL")
                ->select(
                    DB::raw("TRY_CAST(Timestamp AS DATE) AS DateLogged"), 
                    'Timestamp', 
                    'Type', 
                    'AbsentPermission')
                ->orderBy('Timestamp')
                ->get();

            return response()->json($employee, 200);
        } else {
            return response()->json('Employee not found', 404);
        }
    }

    public function payrollAudit(Request $request) {
        $data = DB::table('PayrollExpandedDetails')
            ->whereRaw("Status='Generated'")
            ->select(
                'SalaryPeriod',
                DB::raw("SUM(NetPay) AS NetPay"),
                DB::raw("COUNT(id) AS TotalCount"),
                DB::raw("TRY_CAST(GeneratedDate AS DATE) AS GeneratedDate")
            )
            ->groupByRaw("TRY_CAST(GeneratedDate AS DATE)")
            ->groupBy('SalaryPeriod')
            ->get();

        return view('/payroll_indices/payroll_audit', [
            'data' => $data,
        ]);
    }

    public function payrollAuditReview($salaryPeriod) {
        $departments = DB::table('PayrollExpandedDetails')
            ->whereRaw("Status='Generated' AND SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                'Department',
            )
            ->groupBy('Department')
            ->get();

        $datas = [];
        foreach($departments as $item) {
            array_push($datas, [
                'Department' => $item->Department,
                'Data' => DB::table('PayrollExpandedDetails')
                    ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("PayrollExpandedDetails.Status='Generated' AND PayrollExpandedDetails.Department='" . $item->Department . "' AND PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "'")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'PayrollExpandedDetails.*'
                    )
                    ->get(),
            ]);
        }

        return view('/payroll_indices/payroll_audit_review', [
            'datas' => $datas,
            'salaryPeriod' => $salaryPeriod,
        ]);
    }

    public function auditRejectPayroll(Request $request) {
        $salaryPeriod = $request['SalaryPeriod'];
        $remarks = $request['Remarks'];

        PayrollExpandedDetails::where("SalaryPeriod", $salaryPeriod)
            ->update(['Notes' => $remarks, 'Status' => 'Rejected by Audit', 'AuditedBy' => Auth::id(), 'AuditedDate' => date('Y-m-d H:i:s')]);

        UserFootprints::log('Rejected Payroll Draft', "Rejected payroll draft for salary period " . date('F d, Y', strtotime($salaryPeriod)) . " due to the following reasons: " . $remarks);  

        return response()->json('ok', 200);
    }

    public function auditApprovePayroll(Request $request) {
        $salaryPeriod = $request['SalaryPeriod'];

        PayrollExpandedDetails::where("SalaryPeriod", $salaryPeriod)
            ->update(['Status' => 'Approved By Audit', 'AuditedBy' => Auth::id(), 'AuditedDate' => date('Y-m-d H:i:s')]);

        UserFootprints::log('Payroll Draft Audi Approved', "Payroll draft approved by Audit for salary period " . date('F d, Y', strtotime($salaryPeriod)));  


        /*
         * UPDATE LOANS
         */
        $loans = DB::table('LoanDetails')
            ->leftJoin('Loans', 'LoanDetails.LoanId', '=', 'Loans.id')
            ->whereRaw("LoanDetails.Paid IS NULL AND Loans.EmployeeId IN 
                (SELECT EmployeeId FROM PayrollExpandedDetails WHERE EmployeeId=Loans.EmployeeId AND SalaryPeriod='" . $salaryPeriod . "' AND Status='Approved By Audit')
                AND LoanDetails.Month='" . $salaryPeriod . "'")
            ->select('LoanDetails.id', 'Loans.EmployeeId')
            ->get();
        foreach($loans as $item) {
            LoanDetails::where('id', $item->id)
                ->update(['Paid' => 'Paid']);
        }

        /**
         * UPDATE PaidBills on Employees with attached billing information
         */
        $bills = PayrollBillsAttachments::where('ScheduleDate', $salaryPeriod)->whereNull('Status')->get();
        foreach($bills as $item) {
            $paidBill = PaidBills::where('ServicePeriodEnd', $item->BillingMonth)
                ->where('AccountNumber', $item->AccountNumber)
                ->first();
            if ($paidBill != null) {
                // DOUBLE PAYMENT
                $item->Status = 'Double Payment';
            } else {
                $bill = Bills::where('ServicePeriodEnd', $item->BillingMonth)
                    ->where('AccountNumber', $item->AccountNumber)
                    ->first();
                if ($bill != null) {
                    $sVat = floatval($item->Surcharges) - (floatval($item->Surcharges) / 1.12);

                    $paidBill = new PaidBills;
                    $paidBill->AccountNumber = $bill->AccountNumber;
                    $paidBill->BillNumber = $bill->BillNumber;
                    $paidBill->ServicePeriodEnd = $bill->ServicePeriodEnd;
                    $paidBill->Power = $bill->KWHAmount;
                    $paidBill->Meter = round(floatval($bill->Item2) + $sVat, 2);
                    $paidBill->PR = $bill->PR;
                    $paidBill->Others = $bill->Others;
                    $paidBill->NetAmount = $item->Amount;
                    $paidBill->PaymentType = 'SUB-OFFICE/STATION';
                    $paidBill->ORNumber = null;
                    $paidBill->Teller = env('CASHIER_USER');
                    $paidBill->DCRNumber = "";
                    $paidBill->PostingDate = $item->created_at;
                    $paidBill->PostingSequence = '1';
                    $paidBill->PromptPayment = '0';
                    $paidBill->Surcharge = round($item->Surcharges, 2);
                    $paidBill->save();

                    $item->Status = 'Posted';
                } else {
                    $item->Status = 'Bill Not Found';
                }
            }

            $item->save();
        }

        return response()->json('ok', 200);
    }

    public function viewPayroll($salaryPeriod) {
        $departments = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                'Department',
            )
            ->groupBy('Department')
            ->get();

        $datas = [];
        foreach($departments as $item) {
            array_push($datas, [
                'Department' => $item->Department,
                'Data' => DB::table('PayrollExpandedDetails')
                    ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("PayrollExpandedDetails.Department='" . $item->Department . "' AND PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "'")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'PayrollExpandedDetails.*'
                    )
                    ->orderBy('LastName')
                    ->get(),
            ]);
        }

        $stats = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->orderByDesc('updated_at')
            ->first();

        return view('/payroll_indices/view_payroll', [
            'datas' => $datas,
            'salaryPeriod' => $salaryPeriod,
            'stats' => $stats,
        ]);
    }

    public function removePayroll(Request $request) {
        $salaryPeriod = $request['SalaryPeriod'];

        PayrollExpandedDetails::where("SalaryPeriod", $salaryPeriod)
            ->delete();

        UserFootprints::log('Deleted Payroll Data', 'Deleted payroll data for salary period ' . date('F d, Y', strtotime($salaryPeriod)));

        return response()->json('ok', 200);
    }

    public function withholdingTaxes(Request $request) {
        return view('/payroll_indices/withholding_taxes', [

        ]);
    }

    public function getWithholdingTaxesReportData(Request $request) {
        $year = $request['Year'];
        $department = $request['Department'];

        $from = date('Y-m-d', strtotime('January 1, ' . $year));
        $to = date('Y-m-d', strtotime('December 31, ' . $year));

        if ($department == 'All') {
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
                    'Employees.NoAttendanceAllowed',
                    'Employees.DayOffDates',
                    'Positions.BasicSalary AS SalaryAmount',
                    'Positions.Level',
                    'Positions.Position',
                    'EmployeesDesignations.Status',
            )
            // ->where('EmployeesDesignations.Status', $employeeType)
            ->whereRaw("(EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
            ->orderBy('Employees.LastName')
            ->get();
        } else {
            if ($department == 'SUB-OFFICE') {
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
                            'Employees.NoAttendanceAllowed',
                            'Employees.DayOffDates',
                            'Positions.BasicSalary AS SalaryAmount',
                            'Positions.Level',
                            'Positions.Position',
                            'EmployeesDesignations.Status',
                    )
                    // ->where('EmployeesDesignations.Status', $employeeType)
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
                            'Employees.BiometricsUserId',
                            'Employees.PayrollScheduleId',
                            'Employees.NoAttendanceAllowed',
                            'Employees.DayOffDates',
                            'Positions.BasicSalary AS SalaryAmount',
                            'Positions.Level',
                            'Positions.Position',
                            'EmployeesDesignations.Status',
                    )
                    // ->where('EmployeesDesignations.Status', $employeeType)
                    ->where('Positions.Department', $department)
                    ->whereRaw("Employees.OfficeDesignation NOT IN ('SUB-OFFICE') AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                    ->orderBy('Employees.LastName')
                    ->get();
            }
        }

        foreach($employees as $item) {
            $item->ReportData = DB::table('PayrollExpandedDetails')
                ->whereRaw("EmployeeId='" . $item->id . "' AND (SalaryPeriod BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'SalaryPeriod',
                    'TotalWithholdingTax'
                )
                ->orderBy('SalaryPeriod')
                ->get();
        }

        return response()->json($employees, 200);
    }

    public function viewPayrollWithoutDeduction($salaryPeriod) {
        $departments = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                'Department',
            )
            ->groupBy('Department')
            ->get();

        $datas = [];
        foreach($departments as $item) {
            array_push($datas, [
                'Department' => $item->Department,
                'Data' => DB::table('PayrollExpandedDetails')
                    ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("PayrollExpandedDetails.Department='" . $item->Department . "' AND PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "'")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'PayrollExpandedDetails.*'
                    )
                    ->orderBy('LastName')
                    ->get(),
            ]);
        }

        $stats = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->orderByDesc('updated_at')
            ->first();

        return view('/payroll_indices/view_payroll_without_deduction', [
            'datas' => $datas,
            'salaryPeriod' => $salaryPeriod,
            'stats' => $stats,
        ]);
    }

    public function viewPayrollDeductionsOnly($salaryPeriod) {
        $departments = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                'Department',
            )
            ->groupBy('Department')
            ->get();

        $datas = [];
        foreach($departments as $item) {
            array_push($datas, [
                'Department' => $item->Department,
                'Data' => DB::table('PayrollExpandedDetails')
                    ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("PayrollExpandedDetails.Department='" . $item->Department . "' AND PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "'")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'PayrollExpandedDetails.*'
                    )
                    ->orderBy('LastName')
                    ->get(),
            ]);
        }

        $stats = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->orderByDesc('updated_at')
            ->first();

        return view('/payroll_indices/view_payroll_deduction_only', [
            'datas' => $datas,
            'salaryPeriod' => $salaryPeriod,
            'stats' => $stats,
        ]);
    }

    public function downloadFCBTemplate($salaryPeriod) {
        $dataRaw = DB::table('PayrollExpandedDetails')
            ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
            ->whereRaw("PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                'FirstName',
                'LastName',
                'MiddleName',
                'Suffix',
                'PrimaryBankNumber',
                'PayrollExpandedDetails.*'
            )
            ->orderBy('LastName')
            ->get();

        $data = [];
        $totalPayroll = 0;
        foreach($dataRaw as $item) {
            $totalPayroll += floatval($item->NetPay);
            array_push($data, [
                'PitakardNo' => $item->PrimaryBankNumber,
                'AccountName' => strtoupper(Employees::getMergeNameFull($item)),
                'Amount' => $item->NetPay,
            ]);
        }

        $export = new FCBUploadTemplate($data, $totalPayroll, $salaryPeriod);

        return Excel::download($export, 'FCB-Payroll-Upload-' . $salaryPeriod . '.xlsx');
    }

    public function printFCBSubmission($salaryPeriod) {
        $departments = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                'Department',
            )
            ->groupBy('Department')
            ->get();

        $datas = [];
        foreach($departments as $item) {
            array_push($datas, [
                'Department' => $item->Department,
                'Data' => DB::table('PayrollExpandedDetails')
                    ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("PayrollExpandedDetails.Department='" . $item->Department . "' AND PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "'")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'PrimaryBankNumber',
                        'PayrollExpandedDetails.*'
                    )
                    ->orderBy('LastName')
                    ->get(),
            ]);
        }

        $totalPayroll = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                DB::raw("SUM(NetPay) AS TotalPayroll")
            )
            ->first();

        $payrollClerk = DB::table('Employees')
        ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
        ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
        ->whereRaw("Positions.Position='Payroll Clerk' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
        ->select(
            'FirstName',
            'MiddleName',
            'LastName',
            'Suffix',
            'Positions.Position'
        )
        ->first();

        $osdManager = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Manager, O S D' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $internalAuditor = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Internal Auditor' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $gm = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='General Manager' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        return view('/payroll_indices/print_fcb_submission', [
            'datas' => $datas,
            'salaryPeriod' => $salaryPeriod,
            'totalPayroll' => $totalPayroll,
            'payrollClerk' => $payrollClerk,
            'osdManager' => $osdManager,
            'internalAuditor' => $internalAuditor,
            'gm' => $gm,
        ]);
    }

    public function printPayrollFinal($salaryPeriod) {
        $departments = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                'Department',
            )
            ->groupBy('Department')
            ->get();

        $datas = [];
        foreach($departments as $item) {
            array_push($datas, [
                'Department' => $item->Department,
                'Data' => DB::table('PayrollExpandedDetails')
                    ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                    ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                    ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                    ->whereRaw("PayrollExpandedDetails.Department='" . $item->Department . "' AND PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "'")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'PayrollExpandedDetails.*',
                        'Positions.Position'
                    )
                    ->orderBy('LastName')
                    ->get(),
            ]);
        }

        $totalPayroll = DB::table('PayrollExpandedDetails')
            ->whereRaw("SalaryPeriod='" . $salaryPeriod . "'")
            ->select(
                DB::raw("SUM(NetPay) AS TotalPayroll")
            )
            ->first();
            
        $payrollClerk = DB::table('Employees')
        ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
        ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
        ->whereRaw("Positions.Position='Payroll Clerk' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
        ->select(
            'FirstName',
            'MiddleName',
            'LastName',
            'Suffix',
            'Positions.Position'
        )
        ->first();

        $osdManager = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Manager, O S D' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $internalAuditor = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Internal Auditor' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $gm = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='General Manager' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        return view('/payroll_indices/print_payroll_final', [
            'datas' => $datas,
            'salaryPeriod' => $salaryPeriod,
            'totalPayroll' => $totalPayroll,
            'payrollClerk' => $payrollClerk,
            'osdManager' => $osdManager,
            'internalAuditor' => $internalAuditor,
            'gm' => $gm,
        ]);
    }

    public function getPayrollMonthlyData(Request $request) {
        $employeeId = $request['EmployeeId'];
        $year = $request['Year'];

        $data = DB::table('PayrollExpandedDetails')
            ->whereRaw("EmployeeId='" . $employeeId . "' AND (SalaryPeriod BETWEEN '" . $year . "-01-01' AND '" . $year . "-12-31')")
            ->orderBy('SalaryPeriod')
            ->get();

        return response()->json($data, 200);
    }

    public function zeroOut($salaryPeriod) {
        $data = DB::table('PayrollExpandedDetails')
                    ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "' AND NetPay < 0")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'PayrollExpandedDetails.*'
                    )
                    ->orderBy('PayrollExpandedDetails.Department')
                    ->orderBy('Employees.LastName')
                    ->get();

        return view('/payroll_indices/zero_out', [
            'data' => $data,
            'salaryPeriod' => $salaryPeriod,
        ]);
    }

    public function printZeroOut($salaryPeriod) {
        $data = DB::table('PayrollExpandedDetails')
                    ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("PayrollExpandedDetails.SalaryPeriod='" . $salaryPeriod . "' AND NetPay < 0")
                    ->select(
                        'FirstName',
                        'LastName',
                        'MiddleName',
                        'Suffix',
                        'PayrollExpandedDetails.*'
                    )
                    ->orderBy('PayrollExpandedDetails.Department')
                    ->orderBy('Employees.LastName')
                    ->get();

        return view('/payroll_indices/print_zero_out', [
            'data' => $data,
            'salaryPeriod' => $salaryPeriod,
        ]);
    }
}
