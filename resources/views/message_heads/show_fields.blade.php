<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $messageHeads->id }}</p>
</div>

<!-- Sender Field -->
<div class="col-sm-12">
    {!! Form::label('Sender', 'Sender:') !!}
    <p>{{ $messageHeads->Sender }}</p>
</div>

<!-- Receiver Field -->
<div class="col-sm-12">
    {!! Form::label('Receiver', 'Receiver:') !!}
    <p>{{ $messageHeads->Receiver }}</p>
</div>

<!-- Latestmessage Field -->
<div class="col-sm-12">
    {!! Form::label('LatestMessage', 'Latestmessage:') !!}
    <p>{{ $messageHeads->LatestMessage }}</p>
</div>

