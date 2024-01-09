<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vacation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Vacation', 'Vacation:') !!}
    {!! Form::number('Vacation', null, ['class' => 'form-control']) !!}
</div>

<!-- Sick Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Sick', 'Sick:') !!}
    {!! Form::number('Sick', null, ['class' => 'form-control']) !!}
</div>

<!-- Special Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Special', 'Special:') !!}
    {!! Form::number('Special', null, ['class' => 'form-control']) !!}
</div>

<!-- Maternity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Maternity', 'Maternity:') !!}
    {!! Form::number('Maternity', null, ['class' => 'form-control']) !!}
</div>

<!-- Maternityforsolomother Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MaternityForSoloMother', 'Maternityforsolomother:') !!}
    {!! Form::number('MaternityForSoloMother', null, ['class' => 'form-control']) !!}
</div>

<!-- Paternity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Paternity', 'Paternity:') !!}
    {!! Form::number('Paternity', null, ['class' => 'form-control']) !!}
</div>

<!-- Soloparent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SoloParent', 'Soloparent:') !!}
    {!! Form::number('SoloParent', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>