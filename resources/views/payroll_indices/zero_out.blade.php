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
                <span class="text-muted"><strong>Zero-out</strong> Employee Payroll Data</span>
            </div>
             {{-- ACTIONS --}}
             <div class="col-lg-4">  
                <a href="{{ route('payrollIndices.print-zero-out', [$salaryPeriod]) }}" class="btn btn-primary-skinny float-right"><i class="fas fa-print ico-tab-mini"></i>Print</a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card shadow-none">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Monthly Wage</th>
                        <th>Regular Wage</th>
                        <th>Total Deductions</th>
                        <th>Net Pay</th>
                        <th>Excess Deduction</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td>{{ $item->EmployeeId }}</td>
                                <td>{{ Employees::getMergeNameFormal($item) }}</td>
                                <td class="text-right">{{ $item->MonthlyWage > 0 ? '₱'.number_format($item->MonthlyWage, 2) : '-' }}</td>
                                <td class="text-right">{{ $item->TermWage > 0 ? '₱'.number_format($item->TermWage, 2) : '-' }}</td>
                                <td class="text-right">{{ $item->TotalDeductions > 0 ? '₱'.number_format($item->TotalDeductions, 2) : '-' }}</td>
                                <td class="text-right">₱0</td>
                                <td class="text-right">{{ '(' . number_format(($item->NetPay * -1), 2) . ')' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="right-bottom">
    <a href="{{ route('payrollIndices.view-payroll', [$salaryPeriod]) }}" class="btn-floating shadow bg-success" title="Delete Payroll Entry"><i class="fas fa-arrow-left ico-tab-mini"></i>Full View</a>
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

    </script>
@endpush