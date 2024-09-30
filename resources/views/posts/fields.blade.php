<!-- Postcontent Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('PostContent', 'Postcontent:') !!}
    {!! Form::textarea('PostContent', null, ['class' => 'form-control', 'maxlength' => 16, 'maxlength' => 16]) !!}
</div>

<!-- Postuserid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PostUserId', 'Postuserid:') !!}
    {!! Form::text('PostUserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Priority Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Priority', 'Priority:') !!}
    {!! Form::number('Priority', null, ['class' => 'form-control']) !!}
</div>

<!-- Repostoriginaluserid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RepostOriginalUserId', 'Repostoriginaluserid:') !!}
    {!! Form::text('RepostOriginalUserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Posttype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PostType', 'Posttype:') !!}
    {!! Form::text('PostType', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Repostoriginalpostid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RepostOriginalPostId', 'Repostoriginalpostid:') !!}
    {!! Form::text('RepostOriginalPostId', null, ['class' => 'form-control', 'maxlength' => 90, 'maxlength' => 90]) !!}
</div>

<!-- Privacy Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Privacy', 'Privacy:') !!}
    {!! Form::text('Privacy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Postrawtext Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('PostRawText', 'Postrawtext:') !!}
    {!! Form::textarea('PostRawText', null, ['class' => 'form-control', 'maxlength' => 16, 'maxlength' => 16]) !!}
</div>