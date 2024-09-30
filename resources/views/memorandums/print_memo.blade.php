
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
        font-size: 1em;
    }

    td, th {
      font-size: 1em;
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
        position: relative;
    }

    .col-md-7 {
        width: 58.29%;
        display: inline-table;
    }

    .text-sm {
        font-size: .8em;
    }

    .text-center {
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

<div class="header-print" style="margin-top: 150px;">
    <p><strong>Memoranum No. {{ $memo != null ? $memo->MemoNumber : '' }}</strong></p>

    @if ($printOption === 'with-employee')
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-2">
                <p>To:</p>
            </div>

            @php
                $ttl = count($employees);
                $secondIndexStart = $ttl > 0 ? ceil($ttl/2) : 0;
            @endphp
            {{-- first column --}}
            <div class="col-md-5">
                @foreach ($employees as $key => $item)
                    @if ($key < $secondIndexStart)
                        <p><strong>{{ $item->LastName . ', ' . $item->FirstName }}</strong></p>
                    @endif
                @endforeach
            </div>
            {{-- second column --}}
            <div class="col-md-5">
                @foreach ($employees as $key => $item)
                    @if ($key >= $secondIndexStart)
                        <p><strong>{{ $item->LastName . ', ' . $item->FirstName }}</strong></p>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="row" style="margin-top: 20px;">
        <div class="col-md-2">
            <p>Subject:</p>
        </div>

        <div class="col-md-5">
            <p><strong>{{ $memo != null ? $memo->MemoTitle : '' }}</strong></p>
        </div>
    </div>

    <div class="row" style="border-top: 3px solid black; margin-top: 20px; margin-bottom: 20px; padding-top: 20px;">
        <div class="col-md-12">
            <p style="text-indent: 30px;">{!! $memo != null ? $memo->MemoContent : '' !!}</p>
        </div>

        <div class="col-md-7">

        </div>

        <div class="col-md-5" style="margin-top: 50px;">
            <p class="text-center"><strong>{{ env('GM') }}</strong></p>
            <p class="text-center">General Manager</p>
        </div>

        <div class="col-md-5" style="margin-top: 20px;">
            <p class="text-sm"><i>Copy Furnished:</i></p>
            <div class="row">
                <div class="col-md-5">
                    <p class="text-sm"><i>OGM</i></p>
                    <p class="text-sm"><i>OSD</i></p>
                    <p class="text-sm"><i>ISD</i></p>
                    <p class="text-sm"><i>ESD</i></p>
                    <p class="text-sm"><i>SEEAD</i></p>
                    <p class="text-sm"><i>PGD</i></p>
                    <p class="text-sm"><i>SUB-OFFICE</i></p>
                    <p class="text-sm"><i>HRD File</i></p>
                </div>

                <div class="col-md-5">
                    <p class="text-sm">_____________</p>
                    <p class="text-sm">_____________</p>
                    <p class="text-sm">_____________</p>
                    <p class="text-sm">_____________</p>
                    <p class="text-sm">_____________</p>
                    <p class="text-sm">_____________</p>
                    <p class="text-sm">_____________</p>
                    <p class="text-sm">_____________</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">   
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1000);   
</script>
