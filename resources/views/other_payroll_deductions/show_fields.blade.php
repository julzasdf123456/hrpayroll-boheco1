<!-- Employeeid Field -->
<div class="col-sm-12">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    <p>{{ $otherPayrollDeductions->EmployeeId }}</p>
</div>

<!-- Deductionname Field -->
<div class="col-sm-12">
    {!! Form::label('DeductionName', 'Deductionname:') !!}
    <p>{{ $otherPayrollDeductions->DeductionName }}</p>
</div>

<!-- Deductiondescription Field -->
<div class="col-sm-12">
    {!! Form::label('DeductionDescription', 'Deductiondescription:') !!}
    <p>{{ $otherPayrollDeductions->DeductionDescription }}</p>
</div>

<!-- Scheduledate Field -->
<div class="col-sm-12">
    {!! Form::label('ScheduleDate', 'Scheduledate:') !!}
    <p>{{ $otherPayrollDeductions->ScheduleDate }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $otherPayrollDeductions->Notes }}</p>
</div>

