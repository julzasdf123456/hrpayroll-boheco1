<!-- Tripticketid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TripTicketId', 'Tripticketid:') !!}
    {!! Form::text('TripTicketId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Purpose Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Purpose', 'Purpose:') !!}
    {!! Form::text('Purpose', null, ['class' => 'form-control', 'maxlength' => 2000, 'maxlength' => 2000]) !!}
</div>

<!-- Totalmileage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalMileage', 'Totalmileage:') !!}
    {!! Form::number('TotalMileage', null, ['class' => 'form-control']) !!}
</div>

<!-- Totalliters Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalLiters', 'Totalliters:') !!}
    {!! Form::number('TotalLiters', null, ['class' => 'form-control']) !!}
</div>

<!-- Typeoffuel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TypeOfFuel', 'Typeoffuel:') !!}
    {!! Form::text('TypeOfFuel', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Carratio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CarRatio', 'Carratio:') !!}
    {!! Form::text('CarRatio', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>