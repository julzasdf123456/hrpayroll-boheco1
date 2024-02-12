<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Incentive Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Incentive', 'Incentive:') !!}
    {!! Form::text('Incentive', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Salaryperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SalaryPeriod', 'Salaryperiod:') !!}
    {!! Form::text('SalaryPeriod', null, ['class' => 'form-control','id'=>'SalaryPeriod']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#SalaryPeriod').datepicker()
    </script>
@endpush

<!-- Deducted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Deducted', 'Deducted:') !!}
    {!! Form::text('Deducted', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>