@php
    use App\Models\LeaveBalances;
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
        margin: 0;
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .90em;
    }
    @media print {
        @page {
            
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

    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 3px;
        background-color: #dedede;
        -webkit-print-color-adjust: exact;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
        font-size: 1.2em;
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

    .half {
        display: inline-table; 
        width: 49%;
    }

    .thirty {
        display: inline-table; 
        width: 30%;
    }

    .seventy {
        display: inline-table; 
        width: 69%;
    }

    .watermark {
        position: fixed;
        left: 15%;
        top: 60px;
        width: 65%;
        opacity: 0.16;
        z-index: -99;
        color: white;
        user-select: none;
    }

    .border {
        position: fixed;
        width: 100%;
        z-index: 1;
        color: white;
        left: 0;
        top: 0;
    }

    .pms {
      color: black;
      background: rgb(243, 231, 57);
      padding: 30px;
      font-size: 2em;
      -webkit-print-color-adjust: exact;
    }

    .bg-bill {
      background-color: #607D8B;
      -webkit-print-color-adjust: exact;
    }

    .text-white {
      color: white;
      -webkit-print-color-adjust: exact;
    }

    .text-muted {
        color: #898989;
        -webkit-print-color-adjust: exact;
    }

    .no-pad {
        margin: 0px; 
        padding: 0px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        font-size: .85em;
        border: 1px solid #a8a8a8;
        padding: 5px 8px 5px 8px;
    }

    .header-data {
        padding: 85px 30px 10px 50px;
    }

    .body-data {
        padding: 60px 30px 0px 30px;
    }

</style>

<div id="print-area" class="content">

    <div>
        <p class="text-center"><strong>{{ env('APP_COMPANY') }}</strong></p>
        <p class="text-center">{{ env('APP_LOCATION') }}</p>

        <p class="text-center" style="margin-top: 10px;"><strong>LEAVE CREDIT BALANCES AS OF {{ strtoupper(date('F d, Y', strtotime('last day of ' . $month . ' ' . $year))) }}</strong></p>
    </div>

    {{-- male --}}
    <div style="margin-top: 16px;">
        {{-- <p class="text-muted" style="margin: 5px 0px 5px 0px;">Male Students</p> --}}
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">Employees</th>
                    <th class="text-center" colspan="3">Vacation</th>
                    <th class="text-center" colspan="3">Sick</th>
                    <th class="text-center" rowspan="2">Special</th>
                </tr>
                <tr>
                    <th class="text-center">Days</th>
                    <th class="text-center">Hours</th>
                    <th class="text-center">Mins</th>
                    <th class="text-center">Days</th>
                    <th class="text-center">Hours</th>
                    <th class="text-center">Mins</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    @php
                        $vacation = LeaveBalances::toBalanceArray($item->Vacation);
                        $sick = LeaveBalances::toBalanceArray($item->Sick);
                    @endphp
                    <tr>
                        <td>{{ strtoupper($item->LastName) . ', ' . strtoupper($item->FirstName) }}</td>
                        <td class="text-right">{{ $vacation != null ? ((isset($vacation[0]) && $vacation[0] != null ? $vacation[0] : '0')) : '' }}</td>
                        <td class="text-right">{{ $vacation != null ? ((isset($vacation[1]) && $vacation[1] != null ? $vacation[1] : '0')) : '' }}</td>
                        <td class="text-right">{{ $vacation != null ? ((isset($vacation[2]) && $vacation[2] != null ? $vacation[2] : '0')) : '' }}</td>
                        <td class="text-right">{{ $sick != null ? ((isset($sick[0]) && $sick[0] != null ? $sick[0] : '0')) : '' }}</td>
                        <td class="text-right">{{ $sick != null ? ((isset($sick[1]) && $sick[1] != null ? $sick[1] : '0')) : '' }}</td>
                        <td class="text-right">{{ $sick != null ? ((isset($sick[2]) && $sick[2] != null ? $sick[2] : '0')) : '' }}</td>
                        <td class="text-right">{{ $item->Special }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>