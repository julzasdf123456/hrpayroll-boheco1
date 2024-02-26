<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Payrollid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PayrollId', 'Payrollid:') !!}
    {!! Form::text('PayrollId', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Billingmonth Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillingMonth', 'Billingmonth:') !!}
    {!! Form::text('BillingMonth', null, ['class' => 'form-control','id'=>'BillingMonth']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#BillingMonth').datepicker()
    </script>
@endpush

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::number('Amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Surcharges Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Surcharges', 'Surcharges:') !!}
    {!! Form::number('Surcharges', null, ['class' => 'form-control']) !!}
</div>