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
        font-size: .95em;
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
        border: none;
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
            border: none;
            padding: 3px 5px 3px 5px;
            font-size: .85em;
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
            margin: 0px !important;
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
            width: 48.5%;
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
        margin: 0px !important;
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
        width: 48.5%;
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
    $OverallNetPay = 0;
    $i = 1;
@endphp


<div id="print-area" class="content">
    <div class="row">
        <div class="col-md-6">
            <p class="text-left">{{ strtoupper(env('APP_COMPANY')) }}</p>
            <p class="text-left">{{ env('APP_ADDRESS') }}</p>
            <p class="text-left">Payroll Register</p>
        </div>

        <div class="col-md-6">
            <p class="text-left">Bank Reference: ________________________</p>
            <p class="text-left">Payroll Date:  <span style="border-bottom: 1px solid #242424; margin-left: 20px;">{{ date('m/d/Y') }}</span></p>
            <p class="text-left">Total Payroll: <span style="border-bottom: 1px solid #242424; margin-left: 20px;">{{ $totalPayroll != null ? number_format($totalPayroll->TotalPayroll, 2) : 0 }}</span></p>
        </div>

        <div class="col-md-12" style="margin-top: 20px;">
            <p class="text-left"><strong>PAYROLL </strong> <span style="margin-left: 30px;">{{ date('m/d/Y') }}</span></p>
            <p class="text-left">For {{ date('F d, Y') }}</p>
            <p class="text-left">LIST OF EMPLOYEES PER DEPARTMENT</p>
        </div>
    </div>

    
    <table style="width: 100% !important;">
        <thead>
            <th style="width: 30px;"></th>
            <th style="padding-top: 30px;" colspan="2" class="text-center">Account Name</th>
            <th class="text-center">PITAKARD<br>Account No.</th>
            <th class="text-center">Net Pay<br>Amount</th>
            <th class="text-center"></th>
        </thead>
        <tbody>
        @foreach ($datas as $key => $item)
        @php
            $dataSets = $item['Data'];
        @endphp

            @php
                $NetPay = 0;
            @endphp
            @foreach ($dataSets as $keyx => $itemx)
                @if ($keyx == 0)
                <tr>
                    <th style="padding-top: 30px;" class="text-left" colspan="5">{{ strtoupper(Employees::getDepartmentFull($item['Department'])) }}</th>
                </tr>
                @endif
                <tr>
                    <td>{{ $i }}</td>
                    <td class="text-left">{{ $itemx->LastName }}</td>
                    <td class="text-left">{{ $itemx->FirstName }}</td>
                    <td class="text-right">{{ $itemx->PrimaryBankNumber != null ? $itemx->PrimaryBankNumber : '' }}</td>
                    <td class="text-right">{{ $itemx->NetPay > 0 ? number_format($itemx->NetPay, 2) : '-' }}</td>
                    @php
                        $NetPay += $itemx->NetPay;
                        $i++;
                    @endphp
                    @if ($keyx == (count($dataSets) - 1))
                        <td class="text-right">{{ $NetPay > 0 ? number_format($NetPay, 2) : '-' }}</td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach

            @php
                $OverallNetPay += $NetPay;
            @endphp
            @if ($key == $size-1)
                <tr id="OverAllTotal">
                    <th colspan="4" style="padding-top: 15px;">TOTAL</th>
                    <th class="text-right" style="padding-top: 15px; border-bottom: 2px solid #212121;">{{ $OverallNetPay > 0 ? number_format($OverallNetPay, 2) : '-' }}</th>
                    <th class="text-right" style="padding-top: 15px; border-bottom: 2px solid #212121;">{{ $OverallNetPay > 0 ? number_format($OverallNetPay, 2) : '-' }}</th>
                </tr>
            @endif
    
        @endforeach
        </tbody>
    </table>

    <div class="row" style="margin-top: 28px;">
        <div class="col-md-4">
            <p>Prepared By:</p>
            <br>
            <br>
            <p class="text-center"><strong>{{ $payrollClerk != null ? Employees::getMergeName($payrollClerk) : '' }}</strong></p>
            <p class="text-muted text-center">{{ $payrollClerk->Position }}</p>
        </div>

        <div class="col-md-4">
            <p>Verified By:</p>
            <br>
            <br>
            <p class="text-center"><strong>{{ $osdManager != null ? Employees::getMergeName($osdManager) : '' }}</strong></p>
            <p class="text-muted text-center">{{ $osdManager->Position }}</p>
        </div>
        
        <div class="col-md-4">
            <p>Audited By:</p>
            <br>
            <br>
            <p class="text-center"><strong>{{ $internalAuditor != null ? Employees::getMergeName($internalAuditor) : '' }}</strong></p>
            <p class="text-muted text-center">{{ $internalAuditor->Position }}</p>
        </div>

        <div class="col-md-4">
            
        </div>
        
        <div class="col-md-4" style="margin-top: 30px;">
            <p>Approved By:</p>
            <br>
            <br>
            <p class="text-center"><strong>{{ $gm != null ? Employees::getMergeName($gm) : '' }}</strong></p>
            <p class="text-muted text-center">{{ $gm->Position }}</p>
        </div>
    </div>

</div>

<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>