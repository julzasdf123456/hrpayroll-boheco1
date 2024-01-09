<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('Name', 'Name:') !!}
    <p>{{ $payrollSchedules->Name }}</p>
</div>

<!-- Starttime Field -->
<div class="col-sm-12">
    {!! Form::label('StartTime', 'Starttime:') !!}
    <p>{{ $payrollSchedules->StartTime }}</p>
</div>

<!-- Breakstart Field -->
<div class="col-sm-12">
    {!! Form::label('BreakStart', 'Breakstart:') !!}
    <p>{{ $payrollSchedules->BreakStart }}</p>
</div>

<!-- Breakend Field -->
<div class="col-sm-12">
    {!! Form::label('BreakEnd', 'Breakend:') !!}
    <p>{{ $payrollSchedules->BreakEnd }}</p>
</div>

<!-- Endtime Field -->
<div class="col-sm-12">
    {!! Form::label('EndTime', 'Endtime:') !!}
    <p>{{ $payrollSchedules->EndTime }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $payrollSchedules->Notes }}</p>
</div>

