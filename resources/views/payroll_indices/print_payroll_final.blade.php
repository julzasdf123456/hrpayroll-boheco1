@php
    use App\Models\Employees;
@endphp

<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .80em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
    }

    table {
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #57575a;
        padding: 3px 5px 3px 5px;
    }

    @media print {
        @page {
            /* margin: 10px; */
        }

        header {
            display: none;
        }

        th, td {
            border: 1px solid #57575a;
            padding: 3px 5px 3px 5px;
            font-size: .60em;
        }

        .container {
            width: 100% !important;
        }

        .print-area {
            page-break-before: always;
        }

        .print-area:first-child {
            page-break-before: none;
        }

        .divider {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            background-color: #dedede;
        }

        .left-indent {
            margin-left: 30px;
        }

        p {
            padding: 0px !important;
            margin: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .row {
            width: 100%;
            margin: 0px;
            padding: 0px;
            display: table;
        }

        .col-md-2 {
            width: 16.3%;
            display: inline-table;
        }

        .col-md-10 {
            width: 83.2%;
            display: inline-table;
        }

        .col-md-6 {
            width: 49.5%;
            display: inline-table;
        }

        .col-md-4 {
            width: 33.30%;
            display: inline-table;
        }

        .col-md-3 {
            width: 24.5%;
            display: inline-table;
        }

        .col-md-8 {
            width: 66.62%;
            display: inline-table;
        }

        .col-md-12 {
            width: 99.98%;
            display: inline-table;
        }

        .col-md-5 {
            width: 41.63%;
            display: inline-table;
        }

        .col-md-7 {
            width: 58.29%;
            display: inline-table;
        }
    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .row {
        width: 100%;
        margin: 0px;
        padding: 0px;
        display: table;
    }

    .col-md-2 {
        width: 16.3%;
        display: inline-table;
    }

    .col-md-10 {
        width: 83.2%;
        display: inline-table;
    }

    .col-md-6 {
        width: 49.5%;
        display: inline-table;
    }

    .col-md-4 {
        width: 33.30%;
        display: inline-table;
    }

    .col-md-3 {
        width: 24.5%;
        display: inline-table;
    }

    .col-md-8 {
        width: 66.62%;
        display: inline-table;
    }

    .col-md-12 {
        width: 99.98%;
        display: inline-table;
    }

    .col-md-5 {
        width: 41.63%;
        display: inline-table;
    }

    .col-md-7 {
        width: 58.29%;
        display: inline-table;
    }

    .container {
        width: 100% !important;
    }

    .print-area {
        page-break-before: always;
    }
</style>

@php        
    $size = count($datas);
    
    $OverallMonthlyWage = 0;
    $OverallTermWage = 0;
    $OverallOvertimeHours = 0;
    $OverallOvertimeAmount = 0;
    $OverallAbsentHours = 0;
    $OverallAbsentAmount = 0;
    $OverallLongevity = 0;
    $OverallRiceLaundry = 0;
    $OverallOtherSalaryAdditions = 0;
    $OverallOtherSalaryDeductions = 0;
    $OverallTotalPartialAmount = 0;
    $OverallMotorycleLoan = 0;
    $OverallPagIbigContribution = 0;
    $OverallPagIbigLoan = 0;
    $OverallSSSContribution = 0;
    $OverallSSSLoan = 0;
    $OverallPhilHealthContribution = 0;
    $OverallOtherDeductions = 0;
    $OverallPowerBills = 0;
    $OverallBEMPC = 0;
    $OverallSalaryWithholdingTax = 0;
    $OverallTotalWithholdingTax = 0;
    $OverallTotalDeductions = 0;
    $OverallNetPay = 0;
@endphp


<p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
<p class="text-center">{{ env('APP_ADDRESS') }}</p>
<br>
<h4 style="font-size: 1.2em; margin: 0px; padding: 0px;" class="text-center"><strong>PAYROLL FOR {{ strtoupper(date('F d, Y', strtotime($salaryPeriod))) }}</strong></h4>
<br> 

@foreach ($datas as $key => $item)
@php
    $dataSets = $item['Data'];
@endphp

<div id="print-area" class="content {{ $key > 0 ? 'print-area' : '' }}">
    <table style="width: 100% !important;">
        <thead>
            <tr>
                <th colspan="26">{{ strtoupper(Employees::getDepartmentFull($item['Department'])) }}</th>
            </tr>
            <tr>
                <th rowspan="2">Employee</th>
                <th rowspan="2">Designation</th>
                <th rowspan="2" class="text-center">Rate/Month</th>
                <th rowspan="2" class="text-center">Total Regular Wage</th>
                <th colspan="2" class="text-center">Overtime</th>
                <th colspan="2" class="text-center">Abs/Late/UT</th>
                <th rowspan="2"  class="text-center">Longevity</th>
                <th rowspan="2"  class="text-center">Rice/Laundry</th>
                <th colspan="2"  class="text-center">Other Salary Adj.</th>
                <th rowspan="2"  class="text-center">TOTAL AMOUNT</th>
                <th rowspan="2"  class="text-center">MC Loan</th>
                <th colspan="2"  class="text-center">Pag-Ibig</th>
                <th colspan="2"  class="text-center">SSS</th>
                <th rowspan="2"  class="text-center">PhilHealth Cont.</th>
                <th rowspan="2"  class="text-center">AR Others</th>
                <th rowspan="2"  class="text-center">BOHECO I Bills</th>
                <th rowspan="2"  class="text-center">BEMPC</th>
                <th rowspan="2"  class="text-center">Salary Only WT</th>
                <th rowspan="2"  class="text-center">Total Tax Wheld</th>
                <th rowspan="2"  class="text-center">Total Deductions</th>
                <th rowspan="2"  class="text-center">Net Pay</th>
            </tr>
            <tr>
                <th class="text-center">Hours</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Hours</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Addons</th>
                <th class="text-center">Deductions</th>
                <th class="text-center">Cont.</th>
                <th class="text-center">Loan</th>
                <th class="text-center">Cont.</th>
                <th class="text-center">Loan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $MonthlyWage = 0;
                $TermWage = 0;
                $OvertimeHours = 0;
                $OvertimeAmount = 0;
                $AbsentHours = 0;
                $AbsentAmount = 0;
                $Longevity = 0;
                $RiceLaundry = 0;
                $OtherSalaryAdditions = 0;
                $OtherSalaryDeductions = 0;
                $TotalPartialAmount = 0;
                $MotorycleLoan = 0;
                $PagIbigContribution = 0;
                $PagIbigLoan = 0;
                $SSSContribution = 0;
                $SSSLoan = 0;
                $PhilHealthContribution = 0;
                $OtherDeductions = 0;
                $PowerBills = 0;
                $BEMPC = 0;
                $SalaryWithholdingTax = 0;
                $TotalWithholdingTax = 0;
                $TotalDeductions = 0;
                $NetPay = 0;
            @endphp
            @foreach ($dataSets as $itemx)
                <tr>
                    <td style="width: 200px;">{{ Employees::getMergeNameFormal($itemx) }}</td>
                    <td class="text-left">{{ $itemx->Position }}</td>
                    <td class="text-right">{{ $itemx->MonthlyWage > 0 ? number_format($itemx->MonthlyWage, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->TermWage > 0 ? number_format($itemx->TermWage, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->OvertimeHours > 0 ? round($itemx->OvertimeHours, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->OvertimeAmount > 0 ? number_format($itemx->OvertimeAmount, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->AbsentHours > 0 ? round($itemx->AbsentHours, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->AbsentAmount > 0 ? number_format($itemx->AbsentAmount, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->Longevity > 0 ? number_format($itemx->Longevity, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->RiceLaundry > 0 ? number_format($itemx->RiceLaundry, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->OtherSalaryAdditions > 0 ? number_format($itemx->OtherSalaryAdditions, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->OtherSalaryDeductions > 0 ? number_format($itemx->OtherSalaryDeductions, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->TotalPartialAmount > 0 ? number_format($itemx->TotalPartialAmount, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->MotorycleLoan > 0 ? number_format($itemx->MotorycleLoan, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->PagIbigContribution > 0 ? number_format($itemx->PagIbigContribution, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->PagIbigLoan > 0 ? number_format($itemx->PagIbigLoan, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->SSSContribution > 0 ? number_format($itemx->SSSContribution, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->SSSLoan > 0 ? number_format($itemx->SSSLoan, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->PhilHealthContribution > 0 ? number_format($itemx->PhilHealthContribution, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->OtherDeductions > 0 ? number_format($itemx->OtherDeductions, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->PowerBills > 0 ? '₱'.number_format($itemx->PowerBills, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->BEMPC > 0 ? '₱'.number_format($itemx->BEMPC, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->SalaryWithholdingTax > 0 ? number_format($itemx->SalaryWithholdingTax, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->TotalWithholdingTax > 0 ? number_format($itemx->TotalWithholdingTax, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->TotalDeductions > 0 ? number_format($itemx->TotalDeductions, 2) : '-' }}</td>
                    <td class="text-right"><strong>{{ $itemx->NetPay > 0 ? number_format($itemx->NetPay, 2) : '-' }}</strong></td>
                </tr>
                @php
                    $MonthlyWage += $itemx->MonthlyWage;
                    $TermWage += $itemx->TermWage;
                    $OvertimeHours += $itemx->OvertimeHours;
                    $OvertimeAmount += $itemx->OvertimeAmount;
                    $AbsentHours += $itemx->AbsentHours;
                    $AbsentAmount += $itemx->AbsentAmount;
                    $Longevity += $itemx->Longevity;
                    $RiceLaundry += $itemx->RiceLaundry;
                    $OtherSalaryAdditions += $itemx->OtherSalaryAdditions;
                    $OtherSalaryDeductions += $itemx->OtherSalaryDeductions;
                    $TotalPartialAmount += $itemx->TotalPartialAmount;
                    $MotorycleLoan += $itemx->MotorycleLoan;
                    $PagIbigContribution += $itemx->PagIbigContribution;
                    $PagIbigLoan += $itemx->PagIbigLoan;
                    $SSSContribution += $itemx->SSSContribution;
                    $SSSLoan += $itemx->SSSLoan;
                    $PhilHealthContribution += $itemx->PhilHealthContribution;
                    $OtherDeductions += $itemx->OtherDeductions;
                    $PowerBills += $itemx->PowerBills;
                    $BEMPC += $itemx->BEMPC;
                    $SalaryWithholdingTax += $itemx->SalaryWithholdingTax;
                    $TotalWithholdingTax += $itemx->TotalWithholdingTax;
                    $TotalDeductions += $itemx->TotalDeductions;
                    $NetPay += $itemx->NetPay;
                @endphp
            @endforeach

            <tr>
                <th colspan="2" style="width: 200px;">SUB-TOTAL</th>
                <th class="text-right">{{ $MonthlyWage > 0 ? number_format($MonthlyWage, 2) : '-' }}</th>
                <th class="text-right">{{ $TermWage > 0 ? number_format($TermWage, 2) : '-' }}</th>
                <th class="text-right">{{ $OvertimeHours > 0 ? round($OvertimeHours, 2) : '-' }}</th>
                <th class="text-right">{{ $OvertimeAmount > 0 ? number_format($OvertimeAmount, 2) : '-' }}</th>
                <th class="text-right">{{ $AbsentHours > 0 ? round($AbsentHours, 2) : '-' }}</th>
                <th class="text-right">{{ $AbsentAmount > 0 ? number_format($AbsentAmount, 2) : '-' }}</th>
                <th class="text-right">{{ $Longevity > 0 ? number_format($Longevity, 2) : '-' }}</th>
                <th class="text-right">{{ $RiceLaundry > 0 ? number_format($RiceLaundry, 2) : '-' }}</th>
                <th class="text-right">{{ $OtherSalaryAdditions > 0 ? number_format($OtherSalaryAdditions, 2) : '-' }}</th>
                <th class="text-right">{{ $OtherSalaryDeductions > 0 ? number_format($OtherSalaryDeductions, 2) : '-' }}</th>
                <th class="text-right">{{ $TotalPartialAmount > 0 ? number_format($TotalPartialAmount, 2) : '-' }}</th>
                <th class="text-right">{{ $MotorycleLoan > 0 ? number_format($MotorycleLoan, 2) : '-' }}</th>
                <th class="text-right">{{ $PagIbigContribution > 0 ? number_format($PagIbigContribution, 2) : '-' }}</th>
                <th class="text-right">{{ $PagIbigLoan > 0 ? number_format($PagIbigLoan, 2) : '-' }}</th>
                <th class="text-right">{{ $SSSContribution > 0 ? number_format($SSSContribution, 2) : '-' }}</th>
                <th class="text-right">{{ $SSSLoan > 0 ? number_format($SSSLoan, 2) : '-' }}</th>
                <th class="text-right">{{ $PhilHealthContribution > 0 ? number_format($PhilHealthContribution, 2) : '-' }}</th>
                <th class="text-right">{{ $OtherDeductions > 0 ? number_format($OtherDeductions, 2) : '-' }}</th>
                <th class="text-right">{{ $PowerBills > 0 ? '₱'.number_format($PowerBills, 2) : '-' }}</th>
                <th class="text-right">{{ $BEMPC > 0 ? '₱'.number_format($BEMPC, 2) : '-' }}</th>
                <th class="text-right">{{ $SalaryWithholdingTax > 0 ? number_format($SalaryWithholdingTax, 2) : '-' }}</th>
                <th class="text-right">{{ $TotalWithholdingTax > 0 ? number_format($TotalWithholdingTax, 2) : '-' }}</th>
                <th class="text-right">{{ $TotalDeductions > 0 ? number_format($TotalDeductions, 2) : '-' }}</th>
                <th class="text-right">{{ $NetPay > 0 ? number_format($NetPay, 2) : '-' }}</th>
            </tr>

            @php
                $OverallMonthlyWage += $MonthlyWage;
                $OverallTermWage += $TermWage;
                $OverallOvertimeHours += $OvertimeHours;
                $OverallOvertimeAmount += $OvertimeAmount;
                $OverallAbsentHours += $AbsentHours;
                $OverallAbsentAmount += $AbsentAmount;
                $OverallLongevity += $Longevity;
                $OverallRiceLaundry += $RiceLaundry;
                $OverallOtherSalaryAdditions += $OtherSalaryAdditions;
                $OverallOtherSalaryDeductions += $OtherSalaryDeductions;
                $OverallTotalPartialAmount += $TotalPartialAmount;
                $OverallMotorycleLoan += $MotorycleLoan;
                $OverallPagIbigContribution += $PagIbigContribution;
                $OverallPagIbigLoan += $PagIbigLoan;
                $OverallSSSContribution += $SSSContribution;
                $OverallSSSLoan += $SSSLoan;
                $OverallPhilHealthContribution += $PhilHealthContribution;
                $OverallOtherDeductions += $OtherDeductions;
                $OverallPowerBills += $PowerBills;
                $OverallBEMPC += $BEMPC;
                $OverallSalaryWithholdingTax += $SalaryWithholdingTax;
                $OverallTotalWithholdingTax += $TotalWithholdingTax;
                $OverallTotalDeductions += $TotalDeductions;
                $OverallNetPay += $NetPay;
            @endphp
            @if ($key == $size-1)
                <tr id="OverAllTotal">
                    <th colspan="2" style="width: 200px;">TOTAL</th>
                    <th class="text-right">{{ $OverallMonthlyWage > 0 ? number_format($OverallMonthlyWage, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallTermWage > 0 ? number_format($OverallTermWage, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallOvertimeHours > 0 ? round($OverallOvertimeHours, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallOvertimeAmount > 0 ? number_format($OverallOvertimeAmount, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallAbsentHours > 0 ? round($OverallAbsentHours, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallAbsentAmount > 0 ? number_format($OverallAbsentAmount, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallLongevity > 0 ? number_format($OverallLongevity, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallRiceLaundry > 0 ? number_format($OverallRiceLaundry, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallOtherSalaryAdditions > 0 ? number_format($OverallOtherSalaryAdditions, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallOtherSalaryDeductions > 0 ? number_format($OverallOtherSalaryDeductions, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallTotalPartialAmount > 0 ? number_format($OverallTotalPartialAmount, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallMotorycleLoan > 0 ? number_format($OverallMotorycleLoan, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallPagIbigContribution > 0 ? number_format($OverallPagIbigContribution, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallPagIbigLoan > 0 ? number_format($OverallPagIbigLoan, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallSSSContribution > 0 ? number_format($OverallSSSContribution, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallSSSLoan > 0 ? number_format($OverallSSSLoan, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallPhilHealthContribution > 0 ? number_format($OverallPhilHealthContribution, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallOtherDeductions > 0 ? number_format($OverallOtherDeductions, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallPowerBills > 0 ? '₱'.number_format($OverallPowerBills, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallBEMPC > 0 ? '₱'.number_format($OverallBEMPC, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallSalaryWithholdingTax > 0 ? number_format($OverallSalaryWithholdingTax, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallTotalWithholdingTax > 0 ? number_format($OverallTotalWithholdingTax, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallTotalDeductions > 0 ? number_format($OverallTotalDeductions, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallNetPay > 0 ? number_format($OverallNetPay, 2) : '-' }}</th>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endforeach

<div class="row" style="margin-top: 28px;">
    <div class="col-md-3">
        <p>Prepared By:</p>
        <br>
        <br>
        <p class="text-center"><strong>{{ $payrollClerk != null ? Employees::getMergeName($payrollClerk) : '' }}</strong></p>
        <p class="text-muted text-center">{{ $payrollClerk->Position }}</p>
    </div>

    <div class="col-md-3">
        <p>Verified By:</p>
        <br>
        <br>
        <p class="text-center"><strong>{{ $osdManager != null ? Employees::getMergeName($osdManager) : '' }}</strong></p>
        <p class="text-muted text-center">{{ $osdManager->Position }}</p>
    </div>
    
    <div class="col-md-3">
        <p>Audited By:</p>
        <br>
        <br>
        <p class="text-center"><strong>{{ $internalAuditor != null ? Employees::getMergeName($internalAuditor) : '' }}</strong></p>
        <p class="text-muted text-center">{{ $internalAuditor->Position }}</p>
    </div>
    
    <div class="col-md-3">
        <p>Approved By:</p>
        <br>
        <br>
        <p class="text-center"><strong>{{ $gm != null ? Employees::getMergeName($gm) : '' }}</strong></p>
        <p class="text-muted text-center">{{ $gm->Position }}</p>
    </div>
</div>

<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 2400);
</script>