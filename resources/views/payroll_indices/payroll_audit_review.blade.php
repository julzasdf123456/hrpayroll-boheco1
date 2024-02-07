@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
@push('page_css')
    <style>
        .table {
            font-size: .8em;
        }

        .right-bottom {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }

        .btn-floating {
            height: 40px;
            border-radius: 20px;
            outline: none;
            border: 0px;
            padding-left: 20px;
            padding-right: 20px;
            transition: background-color 0.3s ease;
        }

        .shadow {
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.16); /* Adjust shadow values as needed */
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

<div class="row">
    @foreach ($datas as $item)
        <div class="col-lg-12">
            <div class="card shadow-none">
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
                                    <td class="text-right">{{ $itemx->SalaryWithholdingTax > 0 ? '₱'.number_format($itemx->SalaryWithholdingTax, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TotalWithholdingTax > 0 ? '₱'.number_format($itemx->TotalWithholdingTax, 2) : '-' }}</td>
                                    <td class="text-right">{{ $itemx->TotalDeductions > 0 ? '₱'.number_format($itemx->TotalDeductions, 2) : '-' }}</td>
                                    <td class="text-right"><strong>{{ $itemx->NetPay > 0 ? '₱'.number_format($itemx->NetPay, 2) : '-' }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
    
</div>

<div class="right-bottom">
    <button class="btn-floating shadow bg-success"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
    <button class="btn-floating shadow bg-danger" title="Reject"><i class="fas fa-times"></i></button>
</div>

@endsection


@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
        })
    </script>
@endpush