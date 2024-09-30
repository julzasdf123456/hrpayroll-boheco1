<!-- Commenteruserid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CommenterUserId', 'Commenteruserid:') !!}
    {!! Form::text('CommenterUserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Postid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PostId', 'Postid:') !!}
    {!! Form::text('PostId', null, ['class' => 'form-control', 'maxlength' => 90, 'maxlength' => 90]) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('Comment', 'Comment:') !!}
    {!! Form::textarea('Comment', null, ['class' => 'form-control', 'maxlength' => 16, 'maxlength' => 16]) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Type', 'Type:') !!}
    {!! Form::text('Type', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>