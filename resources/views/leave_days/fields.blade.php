<!-- Leaveid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LeaveId', 'Leaveid:') !!}
    {!! Form::text('LeaveId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Leavedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LeaveDate', 'Leavedate:') !!}
    {!! Form::text('LeaveDate', null, ['class' => 'form-control','id'=>'LeaveDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#LeaveDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Longevity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Longevity', 'Longevity:') !!}
    {!! Form::number('Longevity', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000]) !!}
</div>