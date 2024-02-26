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

<p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
<p class="text-center">{{ env('APP_ADDRESS') }}</p>
<br>
<h4 style="font-size: 1.2em; margin: 0px; padding: 0px;" class="text-center"><strong>ZERO-OUT EMPLOYEES - PAYROLL FOR {{ strtoupper(date('F d, Y', strtotime($salaryPeriod))) }}</strong></h4>
<br> 

<div id="print-area" class="content">
    <table class="table" style="width: 100%;">
        <thead>
            <th>ID</th>
            <th>Employee</th>
            <th>Monthly Wage</th>
            <th>Regular Wage</th>
            <th>Total Deductions</th>
            <th>Net Pay</th>
            <th>Excess Deduction</th>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{ $item->EmployeeId }}</td>
                    <td>{{ Employees::getMergeNameFormal($item) }}</td>
                    <td class="text-right">{{ $item->MonthlyWage > 0 ? '₱'.number_format($item->MonthlyWage, 2) : '-' }}</td>
                    <td class="text-right">{{ $item->TermWage > 0 ? '₱'.number_format($item->TermWage, 2) : '-' }}</td>
                    <td class="text-right">{{ $item->TotalDeductions > 0 ? '₱'.number_format($item->TotalDeductions, 2) : '-' }}</td>
                    <td class="text-right">₱0</td>
                    <td class="text-right">{{ '(' . number_format(($item->NetPay * -1), 2) . ')' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 2400);
</script>