@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Audit Payroll Entries</h4>
            </div>
        </div>
    </div>
</section>

<div class="row" style="padding-left: 20px;">
    @foreach ($data as $item)
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none">
                <div class="card-body">
                    <p class="text-muted" style="margin-top: 15px; font-size: .8em;"><i>Generated on {{ date('F d, Y', strtotime($item->GeneratedDate)) }}</i></p>
                    <span class="text-muted">Payroll Entries for</span>
                    <h4 style="margin-bottom: 18px !important;"><strong>{{ date('F d, Y', strtotime($item->SalaryPeriod)) }}</strong></h4>
                    <span class="text-muted"><i class="fas fa-coins ico-tab"></i>₱ {{ number_format($item->NetPay, 2) }} Total Amount</span><br>
                    <span class="text-muted"><i class="fas fa-user-circle ico-tab"></i>{{ number_format($item->TotalCount) }} Employees</span>
                    
                    <div>
                        <a class="btn btn-default float-right" href="{{ route('payrollIndices.payroll-audit-review', [$item->SalaryPeriod]) }}"><i class="fas fa-eye ico-tab-mini"></i>Review</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection