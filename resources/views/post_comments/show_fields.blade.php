<!-- Commenteruserid Field -->
<div class="col-sm-12">
    {!! Form::label('CommenterUserId', 'Commenteruserid:') !!}
    <p>{{ $postComments->CommenterUserId }}</p>
</div>

<!-- Postid Field -->
<div class="col-sm-12">
    {!! Form::label('PostId', 'Postid:') !!}
    <p>{{ $postComments->PostId }}</p>
</div>

<!-- Comment Field -->
<div class="col-sm-12">
    {!! Form::label('Comment', 'Comment:') !!}
    <p>{{ $postComments->Comment }}</p>
</div>

<!-- Type Field -->
<div class="col-sm-12">
    {!! Form::label('Type', 'Type:') !!}
    <p>{{ $postComments->Type }}</p>
</div>

