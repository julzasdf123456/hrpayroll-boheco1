<!-- Sender Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Sender', 'Sender:') !!}
    {!! Form::text('Sender', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Receiver Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Receiver', 'Receiver:') !!}
    {!! Form::text('Receiver', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Message Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('Message', 'Message:') !!}
    {!! Form::textarea('Message', null, ['class' => 'form-control', 'maxlength' => 16, 'maxlength' => 16]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>