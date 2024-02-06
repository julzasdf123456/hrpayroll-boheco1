<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Year Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Year', 'Year:') !!}
    {!! Form::text('Year', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Incentive Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Incentive', 'Incentive:') !!}
    {!! Form::text('Incentive', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Incentivedescription Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IncentiveDescription', 'Incentivedescription:') !!}
    {!! Form::text('IncentiveDescription', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::number('Amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Istaxable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IsTaxable', 'Istaxable:') !!}
    {!! Form::text('IsTaxable', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Maxuntaxableamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MaxUntaxableAmount', 'Maxuntaxableamount:') !!}
    {!! Form::number('MaxUntaxableAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Deductmonthly Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DeductMonthly', 'Deductmonthly:') !!}
    {!! Form::text('DeductMonthly', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>