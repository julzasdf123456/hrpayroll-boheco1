<!-- Memonumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MemoNumber', 'Memonumber:') !!}
    {!! Form::text('MemoNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Memotitle Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MemoTitle', 'Memotitle:') !!}
    {!! Form::text('MemoTitle', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Memocontent Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('MemoContent', 'Memocontent:') !!}
    {!! Form::textarea('MemoContent', null, ['class' => 'form-control', 'maxlength' => 16, 'maxlength' => 16]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>