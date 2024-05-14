<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    {!! Form::text('id', null, ['class' => 'form-control']) !!}
</div>

<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Leavedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LeaveDate', 'Leavedate:') !!}
    {!! Form::text('LeaveDate', null, ['class' => 'form-control','id'=>'LeaveDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#LeaveDate').datepicker()
    </script>
@endpush

<!-- Hoursabsent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('HoursAbsent', 'Hoursabsent:') !!}
    {!! Form::number('HoursAbsent', null, ['class' => 'form-control']) !!}
</div>