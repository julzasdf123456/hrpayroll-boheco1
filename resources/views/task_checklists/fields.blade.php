<!-- Taskheadid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TaskHeadId', 'Taskheadid:') !!}
    {!! Form::text('TaskHeadId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Title', 'Title:') !!}
    {!! Form::text('Title', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control', 'maxlength' => 2000, 'maxlength' => 2000]) !!}
</div>

<!-- Targetdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TargetDate', 'Targetdate:') !!}
    {!! Form::text('TargetDate', null, ['class' => 'form-control','id'=>'TargetDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#TargetDate').datepicker()
    </script>
@endpush

<!-- Duedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DueDate', 'Duedate:') !!}
    {!! Form::text('DueDate', null, ['class' => 'form-control','id'=>'DueDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DueDate').datepicker()
    </script>
@endpush

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>