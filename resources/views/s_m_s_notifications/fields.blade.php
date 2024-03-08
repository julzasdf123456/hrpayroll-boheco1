<!-- Contactnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ContactNumber', 'Contactnumber:') !!}
    {!! Form::text('ContactNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Message Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Message', 'Message:') !!}
    {!! Form::text('Message', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Source Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Source', 'Source:') !!}
    {!! Form::text('Source', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Sourceid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SourceId', 'Sourceid:') !!}
    {!! Form::text('SourceId', null, ['class' => 'form-control', 'maxlength' => 90, 'maxlength' => 90]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Aifacilitator Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AIFacilitator', 'Aifacilitator:') !!}
    {!! Form::text('AIFacilitator', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>