
@php
   use App\Models\Employees;
@endphp

<style>
@media print {
    p {
        margin: 0px !important;
    }

    html, body {
        margin: 0px !important;
        padding: 0px !important;
        font-family: sans-serif;
        font-size: .9em;
    }

    td, th {
      font-size: .85em;
    }
    
    table {
      border-collapse: collapse;
      width: 100% !important;
    }

    table.bordered td, 
    table.bordered th {
        border: 1px solid #232323;
        padding: 5px;
    }

    .border {
      border: 1px solid #232323;
      padding: 5px;
    }

    .border-side {
      border-left: 1px solid #232323;
      border-right: 1px solid #232323;
      padding: 5px;
    }

    .border-top {
      border-left: 1px solid #232323;
      border-right: 1px solid #232323;
      border-top: 1px solid #232323;
      padding: 5px;
    }

    .border-bottom {
      border-left: 1px solid #232323;
      border-right: 1px solid #232323;
      border-bottom: 1px solid #232323;
      padding: 5px;
    }

    .no-line-spacing {
		padding-top: 0px;
		padding-bottom: 0px;
		margin: 0;
	}

	.checkbox {
		display: inline-block;
		margin-left: 15px;
		margin-right: 5px;
	}

	.clabel {
		margin-left: 22px !important;
		padding-top: 2px;
	}

	.cbox {
		position: absolute;
		border: 1px solid;
		border-color: #232323;
		height: 16px;
		width: 16px;
	}

	.cbox-fill {
		border: 6px solid;
		border-color: #232323;
		position: absolute;
		height: 8px;
		width: 8px;
	}

    .underlined {
		border-bottom: 1px solid;
		border-color: #232323;
		margin-top: -1px;
	}

	.underlined-dotted {
		width: 100%;
		border-bottom: 1px dotted;
		border-color: #232323;
		margin-top: -1px;
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
        width: 45.4%;
        display: inline-table;
        margin: 10px;
    }

    .col-md-4 {
        width: 33.30%;
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

    .center-text {
        text-align: center;
    }

    .right-text {
        text-align: right;
    }

    .left-text {
        text-align: left;
    }

    .width-full {
        width: 100%;
    }

    .inline {
        display: inline-block;
    }

    .no-underline {
        border-bottom: 0px !important;
        text-decoration: none !important;
    }
}
</style>


<div class="header-print">
    <p class="center-text"><strong>{{ env('APP_COMPANY') }}</strong></p>
    <p class="center-text">{{ env('APP_ADDRESS') }}</p>
    <br>
    <p class="center-text"><strong>{{ strtoupper($type) }} LEAVE REPORT</strong></p>
    <p class="center-text">From {{ date('M d, Y', strtotime($from)) }} to {{ date('M d, Y', strtotime($to)) }}</p>
    <br>
    <table class="bordered">
        <thead>
            <th>Employee</th>
            <th>Date Filed</th>
            <th>Leave Days</th>
            <th>Reason</th>
            <th>Status</th>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ Employees::getMergeNameFormal($item) }}</td>
                    <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                    <td>
                        @foreach ($item->Days as $day)
                            <p>{{ date('D, M d, Y', strtotime($day->LeaveDate)) }} ({{ $day->Duration }})</p>
                        @endforeach
                    </td>
                    <td>{{ $item->Content }}</td>
                    <td>{{ $item->Status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript">   
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1000);   
</script>
