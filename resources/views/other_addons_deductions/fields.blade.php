<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Deductionname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DeductionName', 'Deductionname:') !!}
    {!! Form::text('DeductionName', null, ['class' => 'form-control', 'maxlength' => 150, 'maxlength' => 150]) !!}
</div>

<!-- Deductiondescription Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DeductionDescription', 'Deductiondescription:') !!}
    {!! Form::text('DeductionDescription', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Scheduledate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ScheduleDate', 'Scheduledate:') !!}
    {!! Form::text('ScheduleDate', null, ['class' => 'form-control','id'=>'ScheduleDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ScheduleDate').datepicker()
    </script>
@endpush

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Deductionamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DeductionAmount', 'Deductionamount:') !!}
    {!! Form::number('DeductionAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Type', 'Type:') !!}
    {!! Form::text('Type', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Addonname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AddonName', 'Addonname:') !!}
    {!! Form::text('AddonName', null, ['class' => 'form-control', 'maxlength' => 150, 'maxlength' => 150]) !!}
</div>

<!-- Addonamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AddonAmount', 'Addonamount:') !!}
    {!! Form::number('AddonAmount', null, ['class' => 'form-control']) !!}
</div>