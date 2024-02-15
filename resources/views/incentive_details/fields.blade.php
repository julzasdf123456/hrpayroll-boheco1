<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    {!! Form::text('id', null, ['class' => 'form-control']) !!}
</div>

<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Incentivesid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IncentivesId', 'Incentivesid:') !!}
    {!! Form::text('IncentivesId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Subtotal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SubTotal', 'Subtotal:') !!}
    {!! Form::number('SubTotal', null, ['class' => 'form-control']) !!}
</div>

<!-- Basicsalary Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BasicSalary', 'Basicsalary:') !!}
    {!! Form::number('BasicSalary', null, ['class' => 'form-control']) !!}
</div>

<!-- Termwage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TermWage', 'Termwage:') !!}
    {!! Form::number('TermWage', null, ['class' => 'form-control']) !!}
</div>

<!-- Otherdeductions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherDeductions', 'Otherdeductions:') !!}
    {!! Form::number('OtherDeductions', null, ['class' => 'form-control']) !!}
</div>

<!-- Bempc Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BEMPC', 'Bempc:') !!}
    {!! Form::number('BEMPC', null, ['class' => 'form-control']) !!}
</div>

<!-- Netpay Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetPay', 'Netpay:') !!}
    {!! Form::number('NetPay', null, ['class' => 'form-control']) !!}
</div>