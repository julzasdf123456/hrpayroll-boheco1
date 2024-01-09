<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Method Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Method', 'Method:') !!}
    {!! Form::text('Method', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Days Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Days', 'Days:') !!}
    {!! Form::number('Days', null, ['class' => 'form-control']) !!}
</div>

<!-- Details Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Details', 'Details:') !!}
    {!! Form::text('Details', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000]) !!}
</div>