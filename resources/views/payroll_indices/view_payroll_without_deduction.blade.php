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
                <h4><span class="text-muted">Payroll for</span> {{ date('F d, Y', strtotime($salaryPeriod)) }}</h4>
                <span class="text-muted">Payroll data without deductions.</span>
            </div>
             {{-- ACTIONS --}}
             <div class="col-lg-4">  
                <div class="dropdown">
                    <a class="btn btn-link dropdown-toggle float-right {{ Auth::user()->ColorProfile != null ? 'text-white' : '' }}" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="margin-right: 15px;">
                      More
                    </a>
                  
                    <div class="dropdown-menu">
                        <a href="{{ route('payrollIndices.view-payroll', [$salaryPeriod]) }}" class="dropdown-item" style="padding-top: 12px; padding-bottom: 12px;" href="">Original View</a>
                        <a href="{{ route('payrollIndices.view-payroll-deductions-only', [$salaryPeriod]) }}" class="dropdown-item" style="padding-top: 12px; padding-bottom: 12px;" href="">View Payroll Deductions Only</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row" style="padding-bottom: 56px;">
    @php        
        $size = count($datas);
        
        $OverallTotalHoursRendered = 0;
        $OverallTotalWorkedHours = 0;
        $OverallMonthlyWage = 0;
        $OverallTermWage = 0;
        $OverallOvertimeHours = 0;
        $OverallOvertimeAmount = 0;
        $OverallAbsentHours = 0;
        $OverallAbsentAmount = 0;
        $OverallLongevity = 0;
        $OverallRiceLaundry = 0;
        $OverallOtherSalaryAdditions = 0;
        $OverallOtherSalaryDeductions = 0;
        $OverallTotalPartialAmount = 0;
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
                            <tr>
                                <th rowspan="2">Employee</th>
                                <th rowspan="2" class="text-center">Total Hours Rendered</th>
                                <th rowspan="2" class="text-center">Total Working Hours</th>
                                <th rowspan="2" class="text-center">Monthly Wage</th>
                                <th rowspan="2" class="text-center">Term Wage</th>
                                <th colspan="2" class="text-center">Overtime</th>
                                <th colspan="2" class="text-center">Abs/Late/UT</th>
                                <th rowspan="2"  class="text-center">Longevity</th>
                                <th rowspan="2"  class="text-center">Rice/Laundry</th>
                                <th colspan="2"  class="text-center">Other Salary Adj.</th>
                                <th rowspan="2"  class="text-center">TOTAL AMOUNT</th>
                            </tr>
                            <tr>
                                <th class="text-center">Hours</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Hours</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Addons</th>
                                <th class="text-center">Deductions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $TotalHoursRendered = 0;
                                $TotalWorkedHours = 0;
                                $MonthlyWage = 0;
                                $TermWage = 0;
                                $OvertimeHours = 0;
                                $OvertimeAmount = 0;
                                $AbsentHours = 0;
                                $AbsentAmount = 0;
                                $Longevity = 0;
                                $RiceLaundry = 0;
                                $OtherSalaryAdditions = 0;
                                $OtherSalaryDeductions = 0;
                                $TotalPartialAmount = 0;
                            @endphp
                            @foreach ($dataSets as $itemx)
                                <tr>
                                    <td style="width: 200px;">{{ Employees::getMergeNameFormal($itemx) }}</td>
                                    <td class="text-right">{{ $itemx->TotalHoursRendered > 0 ? round($itemx->TotalHoursRendered, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TotalWorkedHours > 0 ? round($itemx->TotalWorkedHours, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->MonthlyWage > 0 ? '₱'.number_format($itemx->MonthlyWage, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TermWage > 0 ? '₱'.number_format($itemx->TermWage, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OvertimeHours > 0 ? round($itemx->OvertimeHours, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OvertimeAmount > 0 ? '₱'.number_format($itemx->OvertimeAmount, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->AbsentHours > 0 ? round($itemx->AbsentHours, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->AbsentAmount > 0 ? '₱'.number_format($itemx->AbsentAmount, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->Longevity > 0 ? '₱'.number_format($itemx->Longevity, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->RiceLaundry > 0 ? '₱'.number_format($itemx->RiceLaundry, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OtherSalaryAdditions > 0 ? '₱'.number_format($itemx->OtherSalaryAdditions, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OtherSalaryDeductions > 0 ? '₱'.number_format($itemx->OtherSalaryDeductions, 2) : '-' }}</td>
                                    <td class="text-right"><strong>{{ $itemx->TotalPartialAmount > 0 ? '₱'.number_format($itemx->TotalPartialAmount, 2) : '-' }}</strong></td>
                                </tr>
                                @php
                                    $TotalHoursRendered += $itemx->TotalHoursRendered;
                                    $TotalWorkedHours += $itemx->TotalWorkedHours;
                                    $MonthlyWage += $itemx->MonthlyWage;
                                    $TermWage += $itemx->TermWage;
                                    $OvertimeHours += $itemx->OvertimeHours;
                                    $OvertimeAmount += $itemx->OvertimeAmount;
                                    $AbsentHours += $itemx->AbsentHours;
                                    $AbsentAmount += $itemx->AbsentAmount;
                                    $Longevity += $itemx->Longevity;
                                    $RiceLaundry += $itemx->RiceLaundry;
                                    $OtherSalaryAdditions += $itemx->OtherSalaryAdditions;
                                    $OtherSalaryDeductions += $itemx->OtherSalaryDeductions;
                                    $TotalPartialAmount += $itemx->TotalPartialAmount;
                                @endphp
                            @endforeach

                            <tr>
                                <th style="width: 200px;">SUB-TOTAL</th>
                                <th class="text-right">{{ $TotalHoursRendered > 0 ? round($TotalHoursRendered, 2) : '-' }}</th>
                                <th class="text-right">{{ $TotalWorkedHours > 0 ? round($TotalWorkedHours, 2) : '-' }}</th>
                                <th class="text-right">{{ $MonthlyWage > 0 ? '₱'.number_format($MonthlyWage, 2) : '-' }}</th>
                                <th class="text-right">{{ $TermWage > 0 ? '₱'.number_format($TermWage, 2) : '-' }}</th>
                                <th class="text-right">{{ $OvertimeHours > 0 ? round($OvertimeHours, 2) : '-' }}</th>
                                <th class="text-right">{{ $OvertimeAmount > 0 ? '₱'.number_format($OvertimeAmount, 2) : '-' }}</th>
                                <th class="text-right">{{ $AbsentHours > 0 ? round($AbsentHours, 2) : '-' }}</th>
                                <th class="text-right">{{ $AbsentAmount > 0 ? '₱'.number_format($AbsentAmount, 2) : '-' }}</th>
                                <th class="text-right">{{ $Longevity > 0 ? '₱'.number_format($Longevity, 2) : '-' }}</th>
                                <th class="text-right">{{ $RiceLaundry > 0 ? '₱'.number_format($RiceLaundry, 2) : '-' }}</th>
                                <th class="text-right">{{ $OtherSalaryAdditions > 0 ? '₱'.number_format($OtherSalaryAdditions, 2) : '-' }}</th>
                                <th class="text-right">{{ $OtherSalaryDeductions > 0 ? '₱'.number_format($OtherSalaryDeductions, 2) : '-' }}</th>
                                <th class="text-right">{{ $TotalPartialAmount > 0 ? '₱'.number_format($TotalPartialAmount, 2) : '-' }}</th>
                            </tr>

                            @php
                                $OverallTotalHoursRendered += $TotalHoursRendered;
                                $OverallTotalWorkedHours += $TotalWorkedHours;
                                $OverallMonthlyWage += $MonthlyWage;
                                $OverallTermWage += $TermWage;
                                $OverallOvertimeHours += $OvertimeHours;
                                $OverallOvertimeAmount += $OvertimeAmount;
                                $OverallAbsentHours += $AbsentHours;
                                $OverallAbsentAmount += $AbsentAmount;
                                $OverallLongevity += $Longevity;
                                $OverallRiceLaundry += $RiceLaundry;
                                $OverallOtherSalaryAdditions += $OtherSalaryAdditions;
                                $OverallOtherSalaryDeductions += $OtherSalaryDeductions;
                                $OverallTotalPartialAmount += $TotalPartialAmount;
                            @endphp
                            @if ($key == $size-1)
                                <tr id="OverAllTotal">
                                    <th style="width: 200px;">TOTAL</th>
                                    <th class="text-right">{{ $OverallTotalHoursRendered > 0 ? round($OverallTotalHoursRendered, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTotalWorkedHours > 0 ? round($OverallTotalWorkedHours, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallMonthlyWage > 0 ? '₱'.number_format($OverallMonthlyWage, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTermWage > 0 ? '₱'.number_format($OverallTermWage, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOvertimeHours > 0 ? round($OverallOvertimeHours, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOvertimeAmount > 0 ? '₱'.number_format($OverallOvertimeAmount, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallAbsentHours > 0 ? round($OverallAbsentHours, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallAbsentAmount > 0 ? '₱'.number_format($OverallAbsentAmount, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallLongevity > 0 ? '₱'.number_format($OverallLongevity, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallRiceLaundry > 0 ? '₱'.number_format($OverallRiceLaundry, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOtherSalaryAdditions > 0 ? '₱'.number_format($OverallOtherSalaryAdditions, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOtherSalaryDeductions > 0 ? '₱'.number_format($OverallOtherSalaryDeductions, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTotalPartialAmount > 0 ? '₱'.number_format($OverallTotalPartialAmount, 2) : '-' }}</th>
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

@canany('god permission')
<div class="right-bottom">
    <a href="{{ route('payrollIndices.view-payroll', [$salaryPeriod]) }}" class="btn-floating shadow bg-success" title="Delete Payroll Entry"><i class="fas fa-arrow-left ico-tab-mini"></i>Original View</a>
</div>
@endcanany

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
                title: "Delete this Payroll Data?",
                text : 'You cannot undo this.',
                showCancelButton: true,
                confirmButtonText: "Proceed Delete",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('payrollIndices.remove-payroll') }}",
                        type : 'GET',
                        data : {
                            SalaryPeriod : "{{ $salaryPeriod }}"
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Payroll deleted!'
                            })
                            window.location.href = "{{ route('payrollIndices.index') }}"
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error deleting payroll!'
                            })
                        }
                    })
                }
            })
            
        }
    </script>
@endpush