<!-- Travelorderid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TravelOrderId', 'Travelorderid:') !!}
    {!! Form::text('TravelOrderId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Rank Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Rank', 'Rank:') !!}
    {!! Form::number('Rank', null, ['class' => 'form-control']) !!}
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