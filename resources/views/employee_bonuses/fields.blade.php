<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Incentive Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Incentive', 'Incentive:') !!}
    {!! Form::text('Incentive', null, ['class' => 'form-control', 'maxlength' => 300, 'maxlength' => 300]) !!}
</div>

<!-- Incentivedescription Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IncentiveDescription', 'Incentivedescription:') !!}
    {!! Form::text('IncentiveDescription', null, ['class' => 'form-control', 'maxlength' => 600, 'maxlength' => 600]) !!}
</div>

<!-- Baseamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BaseAmount', 'Baseamount:') !!}
    {!! Form::number('BaseAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Maxuntaxableamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MaxUntaxableAmount', 'Maxuntaxableamount:') !!}
    {!! Form::number('MaxUntaxableAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Deductuponreceipt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DeductUponReceipt', 'Deductuponreceipt:') !!}
    {!! Form::text('DeductUponReceipt', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Taxdeductionamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TaxDeductionAmount', 'Taxdeductionamount:') !!}
    {!! Form::number('TaxDeductionAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Netamountpay Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetAmountPay', 'Netamountpay:') !!}
    {!! Form::number('NetAmountPay', null, ['class' => 'form-control']) !!}
</div>

<!-- Datereleased Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateReleased', 'Datereleased:') !!}
    {!! Form::text('DateReleased', null, ['class' => 'form-control','id'=>'DateReleased']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateReleased').datepicker()
    </script>
@endpush

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1500, 'maxlength' => 1500]) !!}
</div>

<!-- Year Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Year', 'Year:') !!}
    {!! Form::text('Year', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>