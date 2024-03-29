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
                <span class="text-muted">Only deductions are displayed</span>
            </div>
             {{-- ACTIONS --}}
             <div class="col-lg-4">  
                <div class="dropdown">
                    <a class="btn btn-link dropdown-toggle float-right {{ Auth::user()->ColorProfile != null ? 'text-white' : '' }}" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="margin-right: 15px;">
                      More
                    </a>
                  
                    <div class="dropdown-menu">
                        <a href="{{ route('payrollIndices.view-payroll', [$salaryPeriod]) }}" class="dropdown-item" style="padding-top: 12px; padding-bottom: 12px;" href="">Original View</a>
                        <a href="{{ route('payrollIndices.view-payroll-without-deduction', [$salaryPeriod]) }}" class="dropdown-item" style="padding-top: 12px; padding-bottom: 12px;" href="">View Payroll Without Deductions</a>
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
        $OverallMotorycleLoan = 0;
        $OverallPagIbigContribution = 0;
        $OverallPagIbigLoan = 0;
        $OverallSSSContribution = 0;
        $OverallSSSLoan = 0;
        $OverallPhilHealthContribution = 0;
        $OverallOtherDeductions = 0;
        $OverallSalaryWithholdingTax = 0;
        $OverallTotalWithholdingTax = 0;
        $OverallTotalDeductions = 0;
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
                                <th rowspan="2">Employee</th>
                                <th rowspan="2" class="text-center">Total Hours Rendered</th>
                                <th rowspan="2" class="text-center">Total Working Hours</th>
                                <th rowspan="2" class="text-center">Monthly Wage</th>
                                <th rowspan="2" class="text-center">Term Wage</th>
                                <th rowspan="2"  class="text-center">MC Loan</th>
                                <th colspan="2"  class="text-center">Pag-Ibig</th>
                                <th colspan="2"  class="text-center">SSS</th>
                                <th rowspan="2"  class="text-center">PhilHealth Cont.</th>
                                <th rowspan="2"  class="text-center">AR Others</th>
                                <th rowspan="2"  class="text-center">Salary Only WT</th>
                                <th rowspan="2"  class="text-center">Total Tax Wheld</th>
                                <th rowspan="2"  class="text-center">Total Deductions</th>
                                <th rowspan="2"  class="text-center">Net Pay</th>
                            </tr>
                            <tr>
                                <th class="text-center">Cont.</th>
                                <th class="text-center">Loan</th>
                                <th class="text-center">Cont.</th>
                                <th class="text-center">Loan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $TotalHoursRendered = 0;
                                $TotalWorkedHours = 0;
                                $MonthlyWage = 0;
                                $TermWage = 0;
                                $MotorycleLoan = 0;
                                $PagIbigContribution = 0;
                                $PagIbigLoan = 0;
                                $SSSContribution = 0;
                                $SSSLoan = 0;
                                $PhilHealthContribution = 0;
                                $OtherDeductions = 0;
                                $SalaryWithholdingTax = 0;
                                $TotalWithholdingTax = 0;
                                $TotalDeductions = 0;
                                $NetPay = 0;
                            @endphp
                            @foreach ($dataSets as $itemx)
                                <tr>
                                    <td style="width: 200px;">{{ Employees::getMergeNameFormal($itemx) }}</td>
                                    <td class="text-right">{{ $itemx->TotalHoursRendered > 0 ? round($itemx->TotalHoursRendered, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TotalWorkedHours > 0 ? round($itemx->TotalWorkedHours, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->MonthlyWage > 0 ? '₱'.number_format($itemx->MonthlyWage, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TermWage > 0 ? '₱'.number_format($itemx->TermWage, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->MotorycleLoan > 0 ? '₱'.number_format($itemx->MotorycleLoan, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->PagIbigContribution > 0 ? '₱'.number_format($itemx->PagIbigContribution, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->PagIbigLoan > 0 ? '₱'.number_format($itemx->PagIbigLoan, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->SSSContribution > 0 ? '₱'.number_format($itemx->SSSContribution, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->SSSLoan > 0 ? '₱'.number_format($itemx->SSSLoan, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->PhilHealthContribution > 0 ? '₱'.number_format($itemx->PhilHealthContribution, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OtherDeductions > 0 ? '₱'.number_format($itemx->OtherDeductions, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->SalaryWithholdingTax > 0 ? '₱'.number_format($itemx->SalaryWithholdingTax, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TotalWithholdingTax > 0 ? '₱'.number_format($itemx->TotalWithholdingTax, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TotalDeductions > 0 ? '₱'.number_format($itemx->TotalDeductions, 2) : '-' }}</td>
                                    <td class="text-right"><strong>{{ $itemx->NetPay > 0 ? '₱'.number_format($itemx->NetPay, 2) : '-' }}</strong></td>
                                </tr>
                                @php
                                    $TotalHoursRendered += $itemx->TotalHoursRendered;
                                    $TotalWorkedHours += $itemx->TotalWorkedHours;
                                    $MonthlyWage += $itemx->MonthlyWage;
                                    $TermWage += $itemx->TermWage;
                                    $MotorycleLoan += $itemx->MotorycleLoan;
                                    $PagIbigContribution += $itemx->PagIbigContribution;
                                    $PagIbigLoan += $itemx->PagIbigLoan;
                                    $SSSContribution += $itemx->SSSContribution;
                                    $SSSLoan += $itemx->SSSLoan;
                                    $PhilHealthContribution += $itemx->PhilHealthContribution;
                                    $OtherDeductions += $itemx->OtherDeductions;
                                    $SalaryWithholdingTax += $itemx->SalaryWithholdingTax;
                                    $TotalWithholdingTax += $itemx->TotalWithholdingTax;
                                    $TotalDeductions += $itemx->TotalDeductions;
                                    $NetPay += $itemx->NetPay;
                                @endphp
                            @endforeach

                            <tr>
                                <th style="width: 200px;">SUB-TOTAL</th>
                                <th class="text-right">{{ $TotalHoursRendered > 0 ? round($TotalHoursRendered, 2) : '-' }}</th>
                                <th class="text-right">{{ $TotalWorkedHours > 0 ? round($TotalWorkedHours, 2) : '-' }}</th>
                                <th class="text-right">{{ $MonthlyWage > 0 ? '₱'.number_format($MonthlyWage, 2) : '-' }}</th>
                                <th class="text-right">{{ $TermWage > 0 ? '₱'.number_format($TermWage, 2) : '-' }}</th>
                                <th class="text-right">{{ $MotorycleLoan > 0 ? '₱'.number_format($MotorycleLoan, 2) : '-' }}</th>
                                <th class="text-right">{{ $PagIbigContribution > 0 ? '₱'.number_format($PagIbigContribution, 2) : '-' }}</th>
                                <th class="text-right">{{ $PagIbigLoan > 0 ? '₱'.number_format($PagIbigLoan, 2) : '-' }}</th>
                                <th class="text-right">{{ $SSSContribution > 0 ? '₱'.number_format($SSSContribution, 2) : '-' }}</th>
                                <th class="text-right">{{ $SSSLoan > 0 ? '₱'.number_format($SSSLoan, 2) : '-' }}</th>
                                <th class="text-right">{{ $PhilHealthContribution > 0 ? '₱'.number_format($PhilHealthContribution, 2) : '-' }}</th>
                                <th class="text-right">{{ $OtherDeductions > 0 ? '₱'.number_format($OtherDeductions, 2) : '-' }}</th>
                                <th class="text-right">{{ $SalaryWithholdingTax > 0 ? '₱'.number_format($SalaryWithholdingTax, 2) : '-' }}</th>
                                <th class="text-right">{{ $TotalWithholdingTax > 0 ? '₱'.number_format($TotalWithholdingTax, 2) : '-' }}</th>
                                <th class="text-right">{{ $TotalDeductions > 0 ? '₱'.number_format($TotalDeductions, 2) : '-' }}</th>
                                <th class="text-right">{{ $NetPay > 0 ? '₱'.number_format($NetPay, 2) : '-' }}</th>
                            </tr>

                            @php
                                $OverallTotalHoursRendered += $TotalHoursRendered;
                                $OverallTotalWorkedHours += $TotalWorkedHours;
                                $OverallMonthlyWage += $MonthlyWage;
                                $OverallTermWage += $TermWage;
                                $OverallMotorycleLoan += $MotorycleLoan;
                                $OverallPagIbigContribution += $PagIbigContribution;
                                $OverallPagIbigLoan += $PagIbigLoan;
                                $OverallSSSContribution += $SSSContribution;
                                $OverallSSSLoan += $SSSLoan;
                                $OverallPhilHealthContribution += $PhilHealthContribution;
                                $OverallOtherDeductions += $OtherDeductions;
                                $OverallSalaryWithholdingTax += $SalaryWithholdingTax;
                                $OverallTotalWithholdingTax += $TotalWithholdingTax;
                                $OverallTotalDeductions += $TotalDeductions;
                                $OverallNetPay += $NetPay;
                            @endphp
                            @if ($key == $size-1)
                                <tr id="OverAllTotal">
                                    <th style="width: 200px;">TOTAL</th>
                                    <th class="text-right">{{ $OverallTotalHoursRendered > 0 ? round($OverallTotalHoursRendered, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTotalWorkedHours > 0 ? round($OverallTotalWorkedHours, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallMonthlyWage > 0 ? '₱'.number_format($OverallMonthlyWage, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTermWage > 0 ? '₱'.number_format($OverallTermWage, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallMotorycleLoan > 0 ? '₱'.number_format($OverallMotorycleLoan, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallPagIbigContribution > 0 ? '₱'.number_format($OverallPagIbigContribution, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallPagIbigLoan > 0 ? '₱'.number_format($OverallPagIbigLoan, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallSSSContribution > 0 ? '₱'.number_format($OverallSSSContribution, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallSSSLoan > 0 ? '₱'.number_format($OverallSSSLoan, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallPhilHealthContribution > 0 ? '₱'.number_format($OverallPhilHealthContribution, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOtherDeductions > 0 ? '₱'.number_format($OverallOtherDeductions, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallSalaryWithholdingTax > 0 ? '₱'.number_format($OverallSalaryWithholdingTax, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTotalWithholdingTax > 0 ? '₱'.number_format($OverallTotalWithholdingTax, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTotalDeductions > 0 ? '₱'.number_format($OverallTotalDeductions, 2) : '-' }}</th>
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

<div class="right-bottom">
    <a href="{{ route('payrollIndices.view-payroll', [$salaryPeriod]) }}" class="btn-floating shadow bg-success" title="Delete Payroll Entry"><i class="fas fa-arrow-left ico-tab-mini"></i>Original View</a>
</div>

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