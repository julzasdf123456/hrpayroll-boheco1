<input type="hidden" value="INFO" name="Type">

<!-- Content Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Content', 'Compose:') !!}
    {!! Form::textarea('Content', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000, 'rows' => 4]) !!}
</div>
