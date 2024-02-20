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
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .72em;
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

</style>

<div id="print-area" class="content">
    <p style="margin: 0px; padding: 0px; font-size: .8em;">Generated On: {{ date('F d, Y h:i:s A') }}</p>
    <br>
    <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
    <p class="text-center">{{ env('APP_ADDRESS') }}</p>
    <br>
    <h4 style="font-size: 1.2em;" class="text-center"><strong>LEAVE CREDIT TO CASH CONVERSION</strong></h4>
    <br>  
    <table style="width: 100%;">
        <thead>
            <tr>
                <th rowspan="2" class="text-center" style="border-bottom: 1px solid #454455">Employee</th>
                <th rowspan="2" class="text-center" style="border-bottom: 1px solid #454455">Position</th>
                <th colspan="2" class="text-center" style="border-bottom: 1px solid #454455">Vacation</th>
                <th colspan="2" class="text-center" style="border-bottom: 1px solid #454455">Sick</th>
                <th rowspan="2" class="text-center" style="border-bottom: 1px solid #454455">Total Amount</th>
                <th rowspan="2" class="text-center" style="border-bottom: 1px solid #454455">Date Filed</th>
            </tr>
            <tr>
                <th class="text-center" style="border-bottom: 1px solid #454455"># of Days</th>
                <th class="text-center" style="border-bottom: 1px solid #454455">Amount</th>
                <th class="text-center" style="border-bottom: 1px solid #454455"># of Days</th>
                <th class="text-center" style="border-bottom: 1px solid #454455">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalVacation = 0;
                $totalSick = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td class="text-left">{{ Employees::getMergeNameFormal($item) }}</td>
                    <td class="text-left">{{ $item->Position }}</a></td>
                    <td class="text-right">{{ $item->VacationDays != null && $item->VacationDays > 0 ? number_format($item->VacationDays) : '-' }}</td>
                    <td class="text-right">{{ $item->VacationAmount != null && $item->VacationAmount > 0 ? number_format($item->VacationAmount, 2) : '-' }}</td>
                    <td class="text-right">{{ $item->SickDays != null && $item->SickDays > 0 ? number_format($item->SickDays) : '-' }}</td>
                    <td class="text-right">{{ $item->SickAmount != null && $item->SickAmount > 0 ? number_format($item->SickAmount, 2) : '-' }}</td>
                    <td class="text-right"><strong>{{ number_format($item->SickAmount + $item->VacationAmount, 2) }}</strong></td>
                    <td class="text-right">{{ date('m/d/Y h:i A', strtotime($item->created_at)) }}</td>
                </tr>
                @php
                    $totalVacation += $item->VacationAmount;
                    $totalSick += $item->SickAmount;
                @endphp
            @endforeach
            <tr>
                <td class="text-left" colspan="6"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>{{ number_format($totalSick + $totalVacation, 2) }}</strong></td>
                <td class="text-right"></td>
            </tr>
        </tbody>
    </table>

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
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>