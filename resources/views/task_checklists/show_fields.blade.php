<!-- Taskheadid Field -->
<div class="col-sm-12">
    {!! Form::label('TaskHeadId', 'Taskheadid:') !!}
    <p>{{ $taskChecklists->TaskHeadId }}</p>
</div>

<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('Title', 'Title:') !!}
    <p>{{ $taskChecklists->Title }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('Description', 'Description:') !!}
    <p>{{ $taskChecklists->Description }}</p>
</div>

<!-- Targetdate Field -->
<div class="col-sm-12">
    {!! Form::label('TargetDate', 'Targetdate:') !!}
    <p>{{ $taskChecklists->TargetDate }}</p>
</div>

<!-- Duedate Field -->
<div class="col-sm-12">
    {!! Form::label('DueDate', 'Duedate:') !!}
    <p>{{ $taskChecklists->DueDate }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $taskChecklists->Status }}</p>
</div>

