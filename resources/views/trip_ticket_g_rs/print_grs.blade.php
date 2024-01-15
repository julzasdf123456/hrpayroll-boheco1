
@php
   use App\Models\Employees;
   use App\Models\TripTickets;
   use Illuminate\Support\Facades\Auth;
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
        font-size: 1.08em;
    }

    td, th {
      font-size: 1.05em;
    }
    
    table {
      border-collapse: collapse;
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
		width: 100%;
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
        width: 49.5%;
        display: inline-table;
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
}
</style>


<div class="header-print">
    <div class="row">
        <div class="col-md-12">
            <h4 class="no-line-spacing center-text"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></h4>
            <p class="no-line-spacing center-text">{{ env('APP_ADDRESS') }}</p>
            
        </div>
    </div>

    <br>


</div>

<script type="text/javascript">   
    window.print();

    // window.setTimeout(function(){
    //     window.history.go(-1)
    // }, 1000);   
</script>
