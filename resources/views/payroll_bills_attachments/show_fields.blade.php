<!-- Employeeid Field -->
<div class="col-sm-12">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    <p>{{ $payrollBillsAttachments->EmployeeId }}</p>
</div>

<!-- Payrollid Field -->
<div class="col-sm-12">
    {!! Form::label('PayrollId', 'Payrollid:') !!}
    <p>{{ $payrollBillsAttachments->PayrollId }}</p>
</div>

<!-- Billingmonth Field -->
<div class="col-sm-12">
    {!! Form::label('BillingMonth', 'Billingmonth:') !!}
    <p>{{ $payrollBillsAttachments->BillingMonth }}</p>
</div>

<!-- Amount Field -->
<div class="col-sm-12">
    {!! Form::label('Amount', 'Amount:') !!}
    <p>{{ $payrollBillsAttachments->Amount }}</p>
</div>

<!-- Surcharges Field -->
<div class="col-sm-12">
    {!! Form::label('Surcharges', 'Surcharges:') !!}
    <p>{{ $payrollBillsAttachments->Surcharges }}</p>
</div>

