@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
@push('page_css')
    <style>
        .table-xs {
            font-size: .80em;
        }
    </style>
@endpush
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h4><span class="text-muted">Payroll Audit Review for</span> {{ date('F d, Y', strtotime($salaryPeriod)) }}</h4>
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
        $OverallMotorycleLoan = 0;
        $OverallPagIbigContribution = 0;
        $OverallPagIbigLoan = 0;
        $OverallSSSContribution = 0;
        $OverallSSSLoan = 0;
        $OverallPhilHealthContribution = 0;
        $OverallOtherDeductions = 0;
        $OverallPowerBills = 0;
        $OverallBEMPC = 0;
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
                    <table class="table table-sm table-xs table-bordered table-hover">
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
                                <th rowspan="2"  class="text-center">MC Loan</th>
                                <th colspan="2"  class="text-center">Pag-Ibig</th>
                                <th colspan="2"  class="text-center">SSS</th>
                                <th rowspan="2"  class="text-center">PhilHealth Cont.</th>
                                <th rowspan="2"  class="text-center">AR Others</th>
                                <th rowspan="2"  class="text-center">BOHECO I Bills</th>
                                <th rowspan="2"  class="text-center">BEMPC</th>
                                <th rowspan="2"  class="text-center">Salary Only WT</th>
                                <th rowspan="2"  class="text-center">Total Tax Wheld</th>
                                <th rowspan="2"  class="text-center">Total Deductions</th>
                                <th rowspan="2"  class="text-center">Net Pay</th>
                            </tr>
                            <tr>
                                <th class="text-center">Hours</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Hours</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Addons</th>
                                <th class="text-center">Deductions</th>
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
                                $OvertimeHours = 0;
                                $OvertimeAmount = 0;
                                $AbsentHours = 0;
                                $AbsentAmount = 0;
                                $Longevity = 0;
                                $RiceLaundry = 0;
                                $OtherSalaryAdditions = 0;
                                $OtherSalaryDeductions = 0;
                                $TotalPartialAmount = 0;
                                $MotorycleLoan = 0;
                                $PagIbigContribution = 0;
                                $PagIbigLoan = 0;
                                $SSSContribution = 0;
                                $SSSLoan = 0;
                                $PhilHealthContribution = 0;
                                $OtherDeductions = 0;
                                $PowerBills = 0;
                                $BEMPC = 0;
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
                                    <td class="text-right">{{ $itemx->OvertimeHours > 0 ? round($itemx->OvertimeHours, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OvertimeAmount > 0 ? '₱'.number_format($itemx->OvertimeAmount, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->AbsentHours > 0 ? round($itemx->AbsentHours, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->AbsentAmount > 0 ? '₱'.number_format($itemx->AbsentAmount, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->Longevity > 0 ? '₱'.number_format($itemx->Longevity, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->RiceLaundry > 0 ? '₱'.number_format($itemx->RiceLaundry, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OtherSalaryAdditions > 0 ? '₱'.number_format($itemx->OtherSalaryAdditions, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OtherSalaryDeductions > 0 ? '₱'.number_format($itemx->OtherSalaryDeductions, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TotalPartialAmount > 0 ? '₱'.number_format($itemx->TotalPartialAmount, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->MotorycleLoan > 0 ? '₱'.number_format($itemx->MotorycleLoan, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->PagIbigContribution > 0 ? '₱'.number_format($itemx->PagIbigContribution, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->PagIbigLoan > 0 ? '₱'.number_format($itemx->PagIbigLoan, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->SSSContribution > 0 ? '₱'.number_format($itemx->SSSContribution, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->SSSLoan > 0 ? '₱'.number_format($itemx->SSSLoan, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->PhilHealthContribution > 0 ? '₱'.number_format($itemx->PhilHealthContribution, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->OtherDeductions > 0 ? '₱'.number_format($itemx->OtherDeductions, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->PowerBills > 0 ? '₱'.number_format($itemx->PowerBills, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->BEMPC > 0 ? '₱'.number_format($itemx->BEMPC, 2) : '-' }}</td>
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
                                    $OvertimeHours += $itemx->OvertimeHours;
                                    $OvertimeAmount += $itemx->OvertimeAmount;
                                    $AbsentHours += $itemx->AbsentHours;
                                    $AbsentAmount += $itemx->AbsentAmount;
                                    $Longevity += $itemx->Longevity;
                                    $RiceLaundry += $itemx->RiceLaundry;
                                    $OtherSalaryAdditions += $itemx->OtherSalaryAdditions;
                                    $OtherSalaryDeductions += $itemx->OtherSalaryDeductions;
                                    $TotalPartialAmount += $itemx->TotalPartialAmount;
                                    $MotorycleLoan += $itemx->MotorycleLoan;
                                    $PagIbigContribution += $itemx->PagIbigContribution;
                                    $PagIbigLoan += $itemx->PagIbigLoan;
                                    $SSSContribution += $itemx->SSSContribution;
                                    $SSSLoan += $itemx->SSSLoan;
                                    $PhilHealthContribution += $itemx->PhilHealthContribution;
                                    $OtherDeductions += $itemx->OtherDeductions;
                                    $PowerBills += $itemx->PowerBills;
                                    $BEMPC += $itemx->BEMPC;
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
                                <th class="text-right">{{ $OvertimeHours > 0 ? round($OvertimeHours, 2) : '-' }}</th>
                                <th class="text-right">{{ $OvertimeAmount > 0 ? '₱'.number_format($OvertimeAmount, 2) : '-' }}</th>
                                <th class="text-right">{{ $AbsentHours > 0 ? round($AbsentHours, 2) : '-' }}</th>
                                <th class="text-right">{{ $AbsentAmount > 0 ? '₱'.number_format($AbsentAmount, 2) : '-' }}</th>
                                <th class="text-right">{{ $Longevity > 0 ? '₱'.number_format($Longevity, 2) : '-' }}</th>
                                <th class="text-right">{{ $RiceLaundry > 0 ? '₱'.number_format($RiceLaundry, 2) : '-' }}</th>
                                <th class="text-right">{{ $OtherSalaryAdditions > 0 ? '₱'.number_format($OtherSalaryAdditions, 2) : '-' }}</th>
                                <th class="text-right">{{ $OtherSalaryDeductions > 0 ? '₱'.number_format($OtherSalaryDeductions, 2) : '-' }}</th>
                                <th class="text-right">{{ $TotalPartialAmount > 0 ? '₱'.number_format($TotalPartialAmount, 2) : '-' }}</th>
                                <th class="text-right">{{ $MotorycleLoan > 0 ? '₱'.number_format($MotorycleLoan, 2) : '-' }}</th>
                                <th class="text-right">{{ $PagIbigContribution > 0 ? '₱'.number_format($PagIbigContribution, 2) : '-' }}</th>
                                <th class="text-right">{{ $PagIbigLoan > 0 ? '₱'.number_format($PagIbigLoan, 2) : '-' }}</th>
                                <th class="text-right">{{ $SSSContribution > 0 ? '₱'.number_format($SSSContribution, 2) : '-' }}</th>
                                <th class="text-right">{{ $SSSLoan > 0 ? '₱'.number_format($SSSLoan, 2) : '-' }}</th>
                                <th class="text-right">{{ $PhilHealthContribution > 0 ? '₱'.number_format($PhilHealthContribution, 2) : '-' }}</th>
                                <th class="text-right">{{ $OtherDeductions > 0 ? '₱'.number_format($OtherDeductions, 2) : '-' }}</th>
                                <th class="text-right">{{ $PowerBills > 0 ? '₱'.number_format($PowerBills, 2) : '-' }}</th>
                                <th class="text-right">{{ $BEMPC > 0 ? '₱'.number_format($BEMPC, 2) : '-' }}</th>
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
                                $OverallOvertimeHours += $OvertimeHours;
                                $OverallOvertimeAmount += $OvertimeAmount;
                                $OverallAbsentHours += $AbsentHours;
                                $OverallAbsentAmount += $AbsentAmount;
                                $OverallLongevity += $Longevity;
                                $OverallRiceLaundry += $RiceLaundry;
                                $OverallOtherSalaryAdditions += $OtherSalaryAdditions;
                                $OverallOtherSalaryDeductions += $OtherSalaryDeductions;
                                $OverallTotalPartialAmount += $TotalPartialAmount;
                                $OverallMotorycleLoan += $MotorycleLoan;
                                $OverallPagIbigContribution += $PagIbigContribution;
                                $OverallPagIbigLoan += $PagIbigLoan;
                                $OverallSSSContribution += $SSSContribution;
                                $OverallSSSLoan += $SSSLoan;
                                $OverallPhilHealthContribution += $PhilHealthContribution;
                                $OverallOtherDeductions += $OtherDeductions;
                                $OverallPowerBills += $PowerBills;
                                $OverallBEMPC += $BEMPC;
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
                                    <th class="text-right">{{ $OverallOvertimeHours > 0 ? round($OverallOvertimeHours, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOvertimeAmount > 0 ? '₱'.number_format($OverallOvertimeAmount, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallAbsentHours > 0 ? round($OverallAbsentHours, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallAbsentAmount > 0 ? '₱'.number_format($OverallAbsentAmount, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallLongevity > 0 ? '₱'.number_format($OverallLongevity, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallRiceLaundry > 0 ? '₱'.number_format($OverallRiceLaundry, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOtherSalaryAdditions > 0 ? '₱'.number_format($OverallOtherSalaryAdditions, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOtherSalaryDeductions > 0 ? '₱'.number_format($OverallOtherSalaryDeductions, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallTotalPartialAmount > 0 ? '₱'.number_format($OverallTotalPartialAmount, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallMotorycleLoan > 0 ? '₱'.number_format($OverallMotorycleLoan, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallPagIbigContribution > 0 ? '₱'.number_format($OverallPagIbigContribution, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallPagIbigLoan > 0 ? '₱'.number_format($OverallPagIbigLoan, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallSSSContribution > 0 ? '₱'.number_format($OverallSSSContribution, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallSSSLoan > 0 ? '₱'.number_format($OverallSSSLoan, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallPhilHealthContribution > 0 ? '₱'.number_format($OverallPhilHealthContribution, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallOtherDeductions > 0 ? '₱'.number_format($OverallOtherDeductions, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallPowerBills > 0 ? '₱'.number_format($OverallPowerBills, 2) : '-' }}</th>
                                    <th class="text-right">{{ $OverallBEMPC > 0 ? '₱'.number_format($OverallBEMPC, 2) : '-' }}</th>
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
    <button onclick="approve()" class="btn-floating shadow bg-success"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
    <button onclick="reject()" class="btn-floating shadow bg-danger" title="Reject"><i class="fas fa-times"></i></button>
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

        function reject() {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Remarks/Notes',
                    inputPlaceholder: 'Type your remarks here...',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Reject This Payroll?',
                    text : 'You are about to reject and return this payroll to the finance department for re-checking. Please provide any remarks or notes below for their reference.',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : "{{ route('payrollIndices.audit-reject-payroll') }}",
                        type : "GET",
                        data : {
                            SalaryPeriod : "{{ $salaryPeriod }}",
                            Remarks : text,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'info',
                                text : 'Payroll forwarded back to finance.'
                            })
                            window.location.href = "{{ route('payrollIndices.payroll-audit') }}"
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error rejecting payroll!'
                            })
                        }
                    })
                }
            })()
        }

        function approve() {
            Swal.fire({
                title: "Approve Payroll?",
                text: "By approving, these data will be marked for finalization.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#13946b",
                confirmButtonText: "Approve"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title : 'Approval Commencing',
                        text : 'Approval also locks the data. This may take several seconds...',
                        allowOutsideClick : false,
                        didOpen : () => {
                            Swal.showLoading()
                        }
                    })
                    $.ajax({
                        url : "{{ route('payrollIndices.audit-approve-payroll') }}",
                        type : "GET",
                        data : {
                            SalaryPeriod : "{{ $salaryPeriod }}",
                        },
                        success : function(res) {
                            Swal.close()
                            Toast.fire({
                                icon : 'success',
                                text : 'Payroll approved by audit.'
                            })
                            window.location.href = "{{ route('payrollIndices.payroll-audit') }}"
                        },
                        error : function(err) {
                            Swal.close()
                            Swal.fire({
                                icon : 'error',
                                text : 'Error approving payroll!'
                            })
                        }
                    })
                }
            });
        }
    </script>
@endpush