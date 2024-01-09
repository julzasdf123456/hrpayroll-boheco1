<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Name', 'Schedule Name:') !!}
    {!! Form::text('Name', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Starttime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StartTime', 'Start Time:') !!}
    {!! Form::text('StartTime', null, ['class' => 'form-control']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#StartTime').datetimepicker({
            format: 'HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Breakstart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BreakStart', 'Break Start:') !!}
    {!! Form::text('BreakStart', null, ['class' => 'form-control']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#BreakStart').datetimepicker({
            format: 'HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Breakend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BreakEnd', 'Break End:') !!}
    {!! Form::text('BreakEnd', null, ['class' => 'form-control']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#BreakEnd').datetimepicker({
            format: 'HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Endtime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EndTime', 'End Time:') !!}
    {!! Form::text('EndTime', null, ['class' => 'form-control']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#EndTime').datetimepicker({
            format: 'HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1500,'maxlength' => 1500]) !!}
</div>