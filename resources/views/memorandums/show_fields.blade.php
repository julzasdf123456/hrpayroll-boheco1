<!-- Memonumber Field -->
<div class="col-sm-12">
    {!! Form::label('MemoNumber', 'Memonumber:') !!}
    <p>{{ $memorandums->MemoNumber }}</p>
</div>

<!-- Memotitle Field -->
<div class="col-sm-12">
    {!! Form::label('MemoTitle', 'Memotitle:') !!}
    <p>{{ $memorandums->MemoTitle }}</p>
</div>

<!-- Memocontent Field -->
<div class="col-sm-12">
    {!! Form::label('MemoContent', 'Memocontent:') !!}
    <p>{{ $memorandums->MemoContent }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $memorandums->Status }}</p>
</div>

