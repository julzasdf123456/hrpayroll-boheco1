@php
    use App\Models\Employees;
    
    $colorProf = Auth::user()->ColorProfile;
@endphp

@extends('layouts.app')

@section('content')
@push('page_css')
    <style>
        .table-xs {
            font-size: .70em;
        }

        .hr-timeline {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            width: 100%; /* Occupy entire width */
            margin-top: 20px;
            border-top: 3px solid #686c72;
        }

        .hr-timeline-item {
            flex: 1; /* Each item occupies equal width */
            margin-right: 20px;
            position: relative;
        }

        .bullet {
            width: 20px;
            height: 20px;
            background-color: #686c72; /* Adjust bullet color as needed */
            border-radius: 50%; /* Create a circular bullet */
            position: absolute;
            top: 9%;
            left: 0px;
            transform: translate(0, -100%);
        }

        .content {
            padding: 10px 10px 10px 0px;
            border-radius: 5px;
        }

        .hr-timeline-item:last-child {
            margin-right: 0;
            align-self: flex-end;
        }

    </style>
@endpush
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-8">
                <h4>{{ $incentive->IncentiveName }} 
                    <span class="text-muted">({{ $incentive->Year }})</span>
                    @if ($incentive->Status == 'Locked')
                        <i class="fas fa-check-circle ico-tab-left-mini text-primary" title="This data is locked"></i>
                        <i class="fas fa-lock ico-tab-left-mini" title="This data is locked"></i>
                    @endif
                </h4>
                <span class="text-muted">Generated on: {{ date('F d, Y h:i A', strtotime($incentive->created_at)) }} | Last Updated: {{ date('F d, Y h:i A', strtotime($incentive->updated_at)) }}</span>
            </div>
            <div class="col-lg-4">
                <div class="dropdown">
                    <a class="btn btn-primary-skinny dropdown-toggle float-right {{ $colorProf != null ? 'text-white' : '' }}" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="margin-left: 10px;">
                        More
                    </a>

                    <div class="dropdown-menu">
                        <a href="{{ route('incentives.download-year-end-fcb-template', [$incentive->id]) }}" class="dropdown-item"><i class="fas fa-download ico-tab"></i>Download FCB Upload Format</a>
                        <a href="{{ route('incentives.print-year-end-fcb', [$incentive->id]) }}" class="dropdown-item"><i class="fas fa-print ico-tab"></i>Print FCB Submission</a>
                        <a href="{{ route('incentives.print-year-end-final', [$incentive->id]) }}" class="dropdown-item"><i class="fas fa-print ico-tab"></i>Print Final</a>
                        <a href="{{ route('incentives.print-year-end-signatures', [$incentive->id]) }}" class="dropdown-item"><i class="fas fa-print ico-tab"></i>Print For Receiving and Signature</a>
                    </div>
                </div>
                
                @if ($incentive->Status != 'Locked')
                    <button onclick="lock()" class="btn btn-primary float-right"><i class="fas fa-lock ico-tab-mini"></i>Lock and Finalize</button>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="row" style="padding-bottom: 56px;">
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
        <div class="col-lg-12">
            <div class="card shadow-none" id="{{ $item['Department'] }}">
                <div class="card-header">
                    <span class="card-title"><strong>{{ $item['Department'] }}</strong> <span class="text-muted">Group</span></span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                @php
                    $dataSets = $item['Data'];
                @endphp
                <div class="card-body table-responsive p-0">
                    <table class="table table-xs table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center">Employee</th>
                                <th rowspan="2" class="text-center">Position</th>
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
                                    <td style="width: 340px;">{{ Employees::getMergeNameFormal($itemx) }}</td>
                                    <td style="width: 340px;">{{ $itemx->Position }}</td>
                                    <td class="text-right">{{ $itemx->FourteenthMonthPay > 0 ? '₱'.number_format($itemx->FourteenthMonthPay, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->ThirteenthMonthDifferential !== 0 ? number_format($itemx->ThirteenthMonthDifferential, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->CashGift > 0 ? '₱'.number_format($itemx->CashGift, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->VacationLeave > 0 ? '₱'.number_format($itemx->VacationLeave, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->SickLeave > 0 ? '₱'.number_format($itemx->SickLeave, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->LoyaltyAward > 0 ? '₱'.number_format($itemx->LoyaltyAward, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->SubTotal > 0 ? '₱'.number_format($itemx->SubTotal, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->AROthers > 0 ? '₱'.number_format($itemx->AROthers, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->BEMPC > 0 ? '₱'.number_format($itemx->BEMPC, 2) : '-' }}</td>
                                    <td class="text-right"><strong>{{ $itemx->NetPay > 0 ? '₱'.number_format($itemx->NetPay, 2) : '-' }}</strong></td>
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
                                <th colspan="2" style="width: 200px;">SUB-TOTAL</th>
                                <th class="text-right">{{ $FourteenthMonthPay > 0 ? '₱' . number_format($FourteenthMonthPay, 2) : '-' }}</th>
                                <th class="text-right">{{ $ThirteenthMonthDifferential !== 0 ? number_format($ThirteenthMonthDifferential, 2) : '-' }}</th>
                                <th class="text-right">{{ $CashGift > 0 ? '₱' . number_format($CashGift, 2) : '-' }}</th>
                                <th class="text-right">{{ $VacationLeave > 0 ? '₱' . number_format($VacationLeave, 2) : '-' }}</th>
                                <th class="text-right">{{ $SickLeave > 0 ? '₱' . number_format($SickLeave, 2) : '-' }}</th>
                                <th class="text-right">{{ $LoyaltyAward > 0 ? '₱' . number_format($LoyaltyAward, 2) : '-' }}</th>
                                <th class="text-right">{{ $SubTotal > 0 ? '₱'.number_format($SubTotal, 2) : '-' }}</th>
                                <th class="text-right">{{ $OtherDeductions > 0 ? '₱'.number_format($OtherDeductions, 2) : '-' }}</th>
                                <th class="text-right">{{ $BEMPC > 0 ? '₱'.number_format($BEMPC, 2) : '-' }}</th>
                                <th class="text-right">{{ $NetPay > 0 ? '₱'.number_format($NetPay, 2) : '-' }}</th>
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
                                    <th colspan="2" style="width: 200px;">TOTAL</th>
                                    <th class="text-right">{{ $OverallFourteenthMonthPay > 0 ? '₱' . number_format($OverallFourteenthMonthPay, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallThirteenthMonthDifferential !== 0 ? number_format($OverallThirteenthMonthDifferential, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallCashGift > 0 ? '₱' . number_format($OverallCashGift, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallVacationLeave > 0 ? '₱' . number_format($OverallVacationLeave, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallSickLeave > 0 ? '₱' . number_format($OverallSickLeave, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallLoyaltyAward > 0 ? '₱' . number_format($OverallLoyaltyAward, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallSubTotal > 0 ? '₱'.number_format($OverallSubTotal, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOtherDeductions > 0 ? '₱'.number_format($OverallOtherDeductions, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallBEMPC > 0 ? '₱'.number_format($OverallBEMPC, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallNetPay > 0 ? '₱'.number_format($OverallNetPay, 2) : '-' }}</th>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
    
</div>

<div class="left-bottom bottom-nav-tabs bg-white shadow">
    @foreach ($datas as $item)
        <div class="bottom-nav-pill-container">
            <a class="bottom-nav-pills" href="#{{ $item['Department'] }}">{{ $item['Department'] }}</a>
        </div>        
    @endforeach
    <div class="bottom-nav-pill-container">
        <a class="bottom-nav-pills" href="#OverAllTotal">TOTAL</a>
    </div> 
</div>

@if ($incentive->Status == 'Locked')
    @canany('god permission')
        <div class="right-bottom">
            <button onclick="trash()" class="btn-floating shadow bg-danger" title="Delete Payroll Entry"><i class="fas fa-trash ico-tab-mini"></i>Delete Incentive</button>
        </div>
    @endcanany
@else
    @canany('god permission', 'delete incentive')
        <div class="right-bottom">
            <button onclick="trash()" class="btn-floating shadow bg-danger" title="Delete Payroll Entry"><i class="fas fa-trash ico-tab-mini"></i>Delete Incentive</button>
        </div>
    @endcanany
@endif


@endsection


@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')

            $('a.bottom-nav-pills[href^="#"]').on('click',function (e) {
                e.preventDefault();

                var target = this.hash;
                var $target = $(target);

                $('html, body').stop().animate({
                    'scrollTop': $target.offset().top
                }, 800, 'easeOutCubic', function () {
                    window.location.hash = target;
                });
            });
        })

        function trash() {
            Swal.fire({
                icon : 'warning',
                title: "Delete This Incentive Data?",
                text : 'This cannot be undone.',
                showCancelButton: true,
                confirmButtonText: "Proceed Delete",
                confirmButtonColor : "#e03822"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('incentives.delete') }}",
                        type : 'GET',
                        data : {
                            id : "{{ $incentive->id }}"
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Incentive deleted!'
                            })
                            window.location.href = "{{ route('incentives.index') }}"
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error deleting incentive!'
                            })
                        }
                    })
                }
            })
        }

        function lock() {
            Swal.fire({
                icon : 'warning',
                title: "Confirm Lock?",
                text : 'By locking, the system will prohibit anyone from editing or overriding this incentive data. Proceed with caution.',
                showCancelButton: true,
                confirmButtonText: "Lock",
                confirmButtonColor : "#3a9971"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title : 'Locking Data',
                        text : 'This may take a few seconds. Please wait...',
                        allowOutsideClick : false,
                        didOpen : () => {
                            Swal.showLoading()
                        }
                    })
                    $.ajax({
                        url : "{{ route('incentives.lock-year-end-incentives') }}",
                        type : 'GET',
                        data : {
                            id : "{{ $incentive->id }}"
                        },
                        success : function(res) {
                            Swal.close()
                            Toast.fire({
                                icon : 'success',
                                text : 'Incentive locked!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.close()
                            Toast.fire({
                                icon : 'error',
                                text : 'Error locking incentive!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush