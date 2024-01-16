
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
        font-size: .9em;
    }

    td, th {
      font-size: .85em;
    }
    
    table {
      border-collapse: collapse;
      width: 100% !important;
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
    <div class="row">
        @for ($x = 0; $x < 4; $x++)
            <div class="col-md-6 border">
                <h4 class="no-line-spacing center-text">{{ strtoupper(env('APP_COMPANY')) }}</h4>
                <p class="no-line-spacing center-text">{{ env('APP_ADDRESS') }}</p>
                
                <h4 class="center-text"><strong>GAS REQUISITION SLIP (GRS)</strong></h4>

                <table class="table" style="width: 100% !important;">
                    <tr>
                        <td style="width: 30%;">DATE:</td>
                        <td class="underlined">{{ date('M d, Y', strtotime($tripTicket->DateOfTravel)) }}</td>
                    </tr>
                    <tr>
                        <td>GRS No:</td>
                        <td class="underlined">{{ explode("-", $grs->id)[0] }}</td>
                    </tr>
                    <tr>
                        <td>No. of Liters:</td>
                        <td class="underlined"><strong>{{ $grs->TotalLiters }}</strong></td>
                    </tr>
                    <tr>
                        <td>Fuel Type:</td>
                        <td class="underlined"><strong>{{ strtoupper($grs->TypeOfFuel) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Vehicle:</td>
                        <td class="underlined">{{ $tripTicket->Vehicle }}</td>
                    </tr>
                    <tr>
                        <td>Vehicle No.:</td>
                        <td class="underlined"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; vertical-align: top; padding-top: 10px;">Travel Purpose:</td>
                        <td style="padding-top: 10px;">
                            @php
                                $purpose = explode(";", $tripTicket->PurposeOfTravel);
                            @endphp
                            <ul style="height: 5.2em; overflow: hidden; text-overflow: '>>';">
                                @for ($i = 0; $i<count($purpose); $i++)
                                    <li>{{ $purpose[$i] }}</li>
                                @endfor
                            </ul>                        
                        </td>
                    </tr>
                </table>

                <div class="row">
                    <div class="col-md-7">
                        <h4 class="no-line-spacing center-text underlined">{{ Employees::getDriverMergeName($tripTicket) }}</h4>
                        <p class="no-line-spacing center-text"><i>Driver's Name over Signature</i></p>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-md-5"></div>
                    <div class="col-md-7">
                        <br><br><br>
                        @if ($signatory != null)
                            <h4 class="no-line-spacing center-text underlined">{{ $signatory->name }}</h4>
                        @else
                            <h4 class="no-line-spacing center-text underlined"></h4>
                        @endif
                        
                        <p class="no-line-spacing center-text"><i>Department Manager</i></p>
                    </div>

                    <div class="col-md-12">
                        <br><br>
                        <p class="no-line-spacing center-text"><strong>NOTICE TO THE SUPPLIER</strong></p>
                        <p class="no-line-spacing">1. Each GRS is good for 50 liters of gasoline or 100 liters of diesel fuel.</p>
                        <p class="no-line-spacing">2. Authority to use GRS is not transferable.</p>
                        <p class="no-line-spacing">3. Alternation of any entry shall render the GRS invalid.</p>
                        <p class="no-line-spacing">4. Only the original copy of GRS with full signature and not initials shall be considered to support claims for payment.</p>
                    </div>
                </div>
            </div>
        @endfor
        
    </div>
</div>

<script type="text/javascript">   
    window.print();

    // window.setTimeout(function(){
    //     window.history.go(-1)
    // }, 1000);   
</script>
