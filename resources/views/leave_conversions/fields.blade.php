<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Vacationdays Field -->
<div class="form-group col-sm-6">
    {!! Form::label('VacationDays', 'Vacationdays:') !!}
    {!! Form::number('VacationDays', null, ['class' => 'form-control']) !!}
</div>

<!-- Sickdays Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SickDays', 'Sickdays:') !!}
    {!! Form::number('SickDays', null, ['class' => 'form-control']) !!}
</div>

<!-- Vacationamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('VacationAmount', 'Vacationamount:') !!}
    {!! Form::number('VacationAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Sickamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SickAmount', 'Sickamount:') !!}
    {!! Form::number('SickAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Year Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Year', 'Year:') !!}
    {!! Form::text('Year', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>