@php
    use App\Models\Employees;
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
                <button class="btn btn-primary-skinny float-right" style="margin-left: 10px;"><i class="fas fa-print ico-tab-mini"></i>Print</button>
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
        
        $OverallBasicSalary = 0;
        $OverallTermWage = 0;
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
                    <table class="table table-sm table-bordered table-hover">
                        <thead>
                            <th>Employee</th>
                            <th class="text-center">Basic Salary</th>
                            <th class="text-center">Term Wage</th>
                            <th class="text-center">Total Incentive</th>
                            <th class="text-center">AR Others</th>
                            <th class="text-center">BEMPC</th>
                            <th class="text-center">Net Pay</th>
                        </thead>
                        <tbody>
                            @php
                                $BasicSalary = 0;
                                $TermWage = 0;
                                $SubTotal = 0;
                                $OtherDeductions = 0;
                                $BEMPC = 0;
                                $NetPay = 0;
                            @endphp
                            @foreach ($dataSets as $itemx)
                                <tr>
                                    <td style="width: 340px;">{{ Employees::getMergeNameFormal($itemx) }}</td>
                                    <td class="text-right">{{ $itemx->BasicSalary > 0 ? '₱'.number_format($itemx->BasicSalary, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TermWage > 0 ? '₱'.number_format($itemx->TermWage, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->SubTotal > 0 ? '₱'.number_format($itemx->SubTotal, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OtherDeductions > 0 ? '₱'.number_format($itemx->OtherDeductions, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->BEMPC > 0 ? '₱'.number_format($itemx->BEMPC, 2) : '-' }}</td>
                                    <td class="text-right"><strong>{{ $itemx->NetPay > 0 ? '₱'.number_format($itemx->NetPay, 2) : '-' }}</strong></td>
                                </tr>
                                @php
                                    $BasicSalary += $itemx->BasicSalary;
                                    $TermWage += $itemx->TermWage;
                                    $SubTotal += $itemx->SubTotal;
                                    $OtherDeductions += $itemx->OtherDeductions;
                                    $BEMPC += $itemx->BEMPC;
                                    $NetPay += $itemx->NetPay;
                                @endphp
                            @endforeach

                            <tr>
                                <th style="width: 200px;">SUB-TOTAL</th>
                                <th class="text-right">{{ $BasicSalary > 0 ? '₱'.number_format($BasicSalary, 2) : '-' }}</th>
                                <th class="text-right">{{ $TermWage > 0 ? '₱'.number_format($TermWage, 2) : '-' }}</th>
                                <th class="text-right">{{ $SubTotal > 0 ? '₱'.number_format($SubTotal, 2) : '-' }}</th>
                                <th class="text-right">{{ $OtherDeductions > 0 ? '₱'.number_format($OtherDeductions, 2) : '-' }}</th>
                                <th class="text-right">{{ $BEMPC > 0 ? '₱'.number_format($BEMPC, 2) : '-' }}</th>
                                <th class="text-right">{{ $NetPay > 0 ? '₱'.number_format($NetPay, 2) : '-' }}</th>
                            </tr>

                            @php
                                $OverallBasicSalary += $BasicSalary;
                                $OverallTermWage += $TermWage;
                                $OverallSubTotal += $SubTotal;
                                $OverallOtherDeductions += $OtherDeductions;
                                $OverallBEMPC += $BEMPC;
                                $OverallNetPay += $NetPay;
                            @endphp
                            @if ($key == $size-1)
                                <tr id="OverAllTotal">
                                    <th style="width: 200px;">TOTAL</th>
                                    <th class="text-right">{{ $OverallBasicSalary > 0 ? '₱'.number_format($OverallBasicSalary, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTermWage > 0 ? '₱'.number_format($OverallTermWage, 2) : '-' }}</th>
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
                        url : "{{ route('incentives.lock') }}",
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