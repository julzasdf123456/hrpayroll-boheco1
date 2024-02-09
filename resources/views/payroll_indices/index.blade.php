@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>All Payroll Data</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('payrollIndices.payroll') }}">
                        Generate New <i class="fas fa-arrow-right ico-tab-left-mini"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="row" style="padding-left: 20px;">
        @foreach ($data as $item)
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-none">
                    <div class="card-body">
                        <span class="text-muted">Payroll Entries for</span>
                        <span class="badge bg-info float-right">{{ $item->Status }}</span>
                        <h4 style="margin-bottom: 18px !important;"><strong>{{ date('F d, Y', strtotime($item->SalaryPeriod)) }}</strong></h4>
                        <span class="text-muted"><i class="fas fa-coins ico-tab"></i>₱ {{ number_format($item->NetPay, 2) }} Total Amount</span><br>
                        <span class="text-muted"><i class="fas fa-user-circle ico-tab"></i>{{ number_format($item->TotalCount) }} Employees</span>
                        
                        <div>
                            <span class="text-muted" style="margin-top: 15px; font-size: .8em;"><i>Generated on {{ date('F d, Y', strtotime($item->GeneratedDate)) }}</i></span>
                            <a class="btn btn-default float-right" href="{{ route('payrollIndices.view-payroll', [$item->SalaryPeriod]) }}"><i class="fas fa-eye ico-tab-mini"></i>View</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection

