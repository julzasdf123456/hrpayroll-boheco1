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
    
    $OverallFourteenthMonthPay = 0;
    $OverallThirteenthMonthDifferential = 0;
    $OverallCashGift = 0;
    $OverallVacationLeave = 0;
    $OverallSickLeave = 0;
    $OverallLoyaltyAward = 0;
    $OverallSubTotal = 0;
    $OverallOtherDeductions = 0;
    $OverallBEMPC = 0;
    $OverallNetPay = 0;
@endphp

@foreach ($datas as $key => $item)
@php
    $dataSets = $item['Data'];
@endphp

<div id="print-area" class="content print-area">
    {{-- <p style="margin: 0px; padding: 0px; font-size: .8em;">Generated On: {{ date('F d, Y h:i:s A') }}</p>
    <br> --}}
    <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
    <p class="text-center">{{ env('APP_ADDRESS') }}</p>
    <br>
    <h4 style="font-size: 1.2em; margin: 0px; padding: 0px;" class="text-center"><strong>{{ $incentive->Year }} YEAR END BENEFITS - {{ $item['Department'] }}</strong></h4>
    <br>  
    <table style="width: 100% !important;">
        <thead>
            <tr>
                <th rowspan="2" class="text-center">Employee</th>
                <th rowspan="2" class="text-center">14th Month Pay</th>
                <th rowspan="2" class="text-center">13th Month<br>Differential</th>
                <th rowspan="2" class="text-center">Cash Gift</th>
                <th colspan="2" class="text-center">Leave Conversions</th>
                <th rowspan="2" class="text-center">Loyalty Award</th>
                <th rowspan="2" class="text-center">Total Incentive</th>
                <th rowspan="2" class="text-center">AR Others</th>
                <th rowspan="2" class="text-center">BEMPC</th>
                <th rowspan="2" class="text-center">Net Pay</th>
            </tr>
            <tr>
                <th class="text-center">Vacation</th>
                <th class="text-center">Sick</th>
            </tr>
        </thead>
        <tbody>
            @php
                $FourteenthMonthPay = 0;
                $ThirteenthMonthDifferential = 0;
                $CashGift = 0;
                $VacationLeave = 0;
                $SickLeave = 0;
                $LoyaltyAward = 0;
                $SubTotal = 0;
                $OtherDeductions = 0;
                $BEMPC = 0;
                $NetPay = 0;
            @endphp
            @foreach ($dataSets as $itemx)
                <tr>
                    <td>{{ Employees::getMergeNameFormal($itemx) }}</td>
                    <td class="text-right">{{ $itemx->FourteenthMonthPay > 0 ? number_format($itemx->FourteenthMonthPay, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->ThirteenthMonthDifferential !== 0 ? number_format($itemx->ThirteenthMonthDifferential, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->CashGift > 0 ? number_format($itemx->CashGift, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->VacationLeave > 0 ? number_format($itemx->VacationLeave, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->SickLeave > 0 ? number_format($itemx->SickLeave, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->LoyaltyAward > 0 ? number_format($itemx->LoyaltyAward, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->SubTotal > 0 ? number_format($itemx->SubTotal, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->AROthers > 0 ? number_format($itemx->AROthers, 2) : '-' }}</td>
                    <td class="text-right">{{ $itemx->BEMPC > 0 ? number_format($itemx->BEMPC, 2) : '-' }}</td>
                    <td class="text-right"><strong>{{ $itemx->NetPay > 0 ? number_format($itemx->NetPay, 2) : '-' }}</strong></td>
                </tr>
                @php
                    $FourteenthMonthPay += $itemx->FourteenthMonthPay;
                    $ThirteenthMonthDifferential += $itemx->ThirteenthMonthDifferential;
                    $CashGift += $itemx->CashGift;
                    $VacationLeave += $itemx->VacationLeave;
                    $SickLeave += $itemx->SickLeave;
                    $LoyaltyAward += $itemx->LoyaltyAward;
                    $SubTotal += $itemx->SubTotal;
                    $OtherDeductions += $itemx->AROthers;
                    $BEMPC += $itemx->BEMPC;
                    $NetPay += $itemx->NetPay;
                @endphp
            @endforeach

            <tr>
                <th>SUB-TOTAL</th>
                <th class="text-right">{{ $FourteenthMonthPay > 0 ? number_format($FourteenthMonthPay, 2) : '-' }}</th>
                <th class="text-right">{{ $ThirteenthMonthDifferential !== 0 ? number_format($ThirteenthMonthDifferential, 2) : '-' }}</th>
                <th class="text-right">{{ $CashGift > 0 ? number_format($CashGift, 2) : '-' }}</th>
                <th class="text-right">{{ $VacationLeave > 0 ? number_format($VacationLeave, 2) : '-' }}</th>
                <th class="text-right">{{ $SickLeave > 0 ? number_format($SickLeave, 2) : '-' }}</th>
                <th class="text-right">{{ $LoyaltyAward > 0 ? number_format($LoyaltyAward, 2) : '-' }}</th>
                <th class="text-right">{{ $SubTotal > 0 ? number_format($SubTotal, 2) : '-' }}</th>
                <th class="text-right">{{ $OtherDeductions > 0 ? number_format($OtherDeductions, 2) : '-' }}</th>
                <th class="text-right">{{ $BEMPC > 0 ? number_format($BEMPC, 2) : '-' }}</th>
                <th class="text-right">{{ $NetPay > 0 ? number_format($NetPay, 2) : '-' }}</th>
            </tr>

            @php
                $OverallFourteenthMonthPay += $FourteenthMonthPay;
                $OverallThirteenthMonthDifferential += $ThirteenthMonthDifferential;
                $OverallCashGift += $CashGift;
                $OverallVacationLeave += $VacationLeave;
                $OverallSickLeave += $SickLeave;
                $OverallLoyaltyAward += $LoyaltyAward;
                $OverallSubTotal += $SubTotal;
                $OverallOtherDeductions += $OtherDeductions;
                $OverallBEMPC += $BEMPC;
                $OverallNetPay += $NetPay;
            @endphp
            @if ($key == $size-1)
                <tr id="OverAllTotal">
                    <th>TOTAL</th>
                    <th class="text-right">{{ $OverallFourteenthMonthPay > 0 ? number_format($OverallFourteenthMonthPay, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallThirteenthMonthDifferential !== 0 ? number_format($OverallThirteenthMonthDifferential, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallCashGift > 0 ? number_format($OverallCashGift, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallVacationLeave > 0 ? number_format($OverallVacationLeave, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallSickLeave > 0 ? number_format($OverallSickLeave, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallLoyaltyAward > 0 ? number_format($OverallLoyaltyAward, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallSubTotal > 0 ? number_format($OverallSubTotal, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallOtherDeductions > 0 ? number_format($OverallOtherDeductions, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallBEMPC > 0 ? number_format($OverallBEMPC, 2) : '-' }}</th>
                    <th class="text-right">{{ $OverallNetPay > 0 ? number_format($OverallNetPay, 2) : '-' }}</th>
                </tr>
            @endif
        </tbody>
    </table>

    @if ($key == $size-1)
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
    @endif
    
</div>
@endforeach

<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>