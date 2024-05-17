<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    {!! Form::text('id', null, ['class' => 'form-control']) !!}
</div>

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

<!-- Latestmessage Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('LatestMessage', 'Latestmessage:') !!}
    {!! Form::textarea('LatestMessage', null, ['class' => 'form-control', 'maxlength' => 16, 'maxlength' => 16]) !!}
</div>