<!-- Preparedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PreparedBy', 'Preparedby:') !!}
    {!! Form::text('PreparedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Dateprepared Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DatePrepared', 'Dateprepared:') !!}
    {!! Form::text('DatePrepared', null, ['class' => 'form-control','id'=>'DatePrepared']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DatePrepared').datepicker()
    </script>
@endpush

<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Dateofduty Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateOfDuty', 'Dateofduty:') !!}
    {!! Form::text('DateOfDuty', null, ['class' => 'form-control','id'=>'DateOfDuty']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateOfDuty').datepicker()
    </script>
@endpush

<!-- Timestart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TimeStart', 'Timestart:') !!}
    {!! Form::text('TimeStart', null, ['class' => 'form-control']) !!}
</div>

<!-- Timeend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TimeEnd', 'Timeend:') !!}
    {!! Form::text('TimeEnd', null, ['class' => 'form-control']) !!}
</div>

<!-- Purposeofduty Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PurposeOfDuty', 'Purposeofduty:') !!}
    {!! Form::text('PurposeOfDuty', null, ['class' => 'form-control', 'maxlength' => 3000, 'maxlength' => 3000]) !!}
</div>

<!-- Dateofoffset Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateOfOffset', 'Dateofoffset:') !!}
    {!! Form::text('DateOfOffset', null, ['class' => 'form-control','id'=>'DateOfOffset']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateOfOffset').datepicker()
    </script>
@endpush

<!-- Offsetreason Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OffsetReason', 'Offsetreason:') !!}
    {!! Form::text('OffsetReason', null, ['class' => 'form-control', 'maxlength' => 3000, 'maxlength' => 3000]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>