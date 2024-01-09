<!-- Barangays Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Barangays', 'Barangays:') !!}
    {!! Form::text('Barangays', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Townid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TownId', 'Townid:') !!}
    {!! Form::text('TownId', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>