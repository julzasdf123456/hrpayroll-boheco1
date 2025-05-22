@php
    use App\Models\Employees;
    use App\Models\TripTicketSignatories;
@endphp
<html>

<head>
    <title>Printing a Trip Ticket</title>
</head>

<body>
    <!-- <h1>Driver's Trip Ticket</h1> -->
    <table class="table">
        <thead>
            <tr>
                <th class="text-center" style="padding: 8px;" colspan="2">
                    <p style="font-size: 24px; ">DRIVER'S TRIP TICKET</p>
                    <p>Printed at {{ now()->format('M d, Y') }}</p>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">
                    Requisitioner
                </td>
                <td class="font-bold">
                    {{ Employees::getMergeNameFormal(Employees::find($tripTicket->EmployeeId)) }}
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    Date of Travel
                </td>
                <td>
                    {{ $tripTicket->DateOfTravel }}
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    Purpose
                </td>
                <td>
                    {{ $tripTicket->PurposeOfTravel }}
                </td>
            </tr>
            <!-- @if ($tripTicket->Driver !== $tripTicket->EmployeeId)
            <tr>
                <td class="text-center">
                    Driver to Travel
                </td>
                <td>
                    {{ Employees::getMergeNameFormal(Employees::find($tripTicket->Driver)) }}
                </td>
            </tr>
            @endif -->
            <tr>
                <td class="text-center">
                    Vehicle to Travel
                </td>
                <td>
                    <span>{{ $tripTicket->Vehicle }}</span>
                    @if ($tripTicket->Driver !== $tripTicket->EmployeeId)
                    <br />
                    <span>Driver: {{ Employees::getMergeNameFormal(Employees::find($tripTicket->Driver)) }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    Passengers
                </td>
                <td>
                    @if ($passengers->count() < 1)
                        --- NONE ---
                        @else
                        <ul>
                        @foreach ($passengers as $passenger)
                        <li>{{ Employees::getMergeNameFormal($passenger) }}</li>
                        @endforeach
                        </ul>
                        @endif
                </td>
            </tr>
            <tr>
                <td class="text-center">Trip Destinations</td>
                <td>
                    @if ($destinations->count() < 1)
                        --- NO DESTINATIONS ---
                        @else
                        <ul>
                        @foreach ($destinations as $destination)
                        <li>{{ $destination->DestinationAddress }}</li>
                        @endforeach
                        </ul>
                        @endif
                </td>
            </tr>
        </tbody>
    </table>
    <div style="padding-top: 12px; display: flex; justify-content: center; gap: 6vw;">
        <div style="font-size: 12px; display: flex; flex-direction: column;">
            <p>DEPARTURE</p>
            <br />
            <br />
            <p>Date: ____________________</p>
            <br />
            <p>Time: ____________________</p>
        </div>
        <div style="font-size: 12px; display: flex; flex-direction: column;">
            <p>ARRIVAL</p>
            <br />
            <br />
            <p>Date: ____________________</p>
            <br />
            <p>Time: ____________________</p>
        </div>
        <div style="font-size: 12px; display: flex; flex-direction: column;">
            @if ($tripTicket->Status === "APPROVED")
                <p>ALREADY APPROVED & SIGNED BY:</p>
            @else
                <p>TO BE APPROVED BY:</p>
            @endif
            <br />
            <br />
            <br />
            <p style="font-weight: bold; font-size: 16px; text-align: center; margin-left: auto; margin-right: auto;">{{ $signatory['FirstName'] . " " . $signatory['MiddleName'] . " " . $signatory['LastName'] . " " . $signatory['Suffix'] }}</p>
            
            <p style="font-weight: lighter; font-size: 12px; text-align: center; margin-left: auto; margin-right: auto;">{{ $signatory['Position'] }}</p>
        </div>
    </div>
</body>

</html>

<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }

    html,
    body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        margin: 0;
        font-size: .85em;


        padding-top: 10px;
        padding-right: 15px;
        padding-bottom: 10px;
        padding-left: 15px;
    }

    table tbody th,
    td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .90em;
    }

    .font-bold {
        font-weight: bold;
    }

    @media print {
        @page {}

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
        width: 32.5%;
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

    .table th,
    .table td {
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
<script>
    window.print();
    // window.setInterval(()=>{    
    //     window.close();
    // }, 3000)
</script>