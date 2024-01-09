<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Dateofleave Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateOfLeave', 'Dateofleave:') !!}
    {!! Form::text('DateOfLeave', null, ['class' => 'form-control','id'=>'DateOfLeave']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateOfLeave').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Leaveid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LeaveId', 'Leaveid:') !!}
    {!! Form::text('LeaveId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>