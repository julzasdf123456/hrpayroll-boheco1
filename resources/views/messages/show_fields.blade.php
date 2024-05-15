<!-- Sender Field -->
<div class="col-sm-12">
    {!! Form::label('Sender', 'Sender:') !!}
    <p>{{ $messages->Sender }}</p>
</div>

<!-- Receiver Field -->
<div class="col-sm-12">
    {!! Form::label('Receiver', 'Receiver:') !!}
    <p>{{ $messages->Receiver }}</p>
</div>

<!-- Message Field -->
<div class="col-sm-12">
    {!! Form::label('Message', 'Message:') !!}
    <p>{{ $messages->Message }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $messages->Status }}</p>
</div>

