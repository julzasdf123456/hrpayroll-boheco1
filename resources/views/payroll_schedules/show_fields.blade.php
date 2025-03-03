@php
    use Carbon\Carbon;
@endphp

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('Name', 'Name:') !!}
    <p>{{ $payrollSchedules->Name }}</p>
</div>

<!-- Starttime Field -->
<div class="col-sm-12">
    {!! Form::label('StartTime', 'Starttime:') !!}
    <p>{{ Carbon::parse($payrollSchedules->StartTime)->format('g:i A') }}</p>
</div>

<!-- Breakstart Field -->
<div class="col-sm-12">
    {!! Form::label('BreakStart', 'Breakstart:') !!}
    <p>{{ Carbon::parse($payrollSchedules->BreakStart)->format('g:i A') }}</p>
</div>

<!-- Breakend Field -->
<div class="col-sm-12">
    {!! Form::label('BreakEnd', 'Breakend:') !!}
    <p>{{ Carbon::parse($payrollSchedules->BreakEnd)->format('g:i A') }}</p>
</div>

<!-- Endtime Field -->
<div class="col-sm-12">
    {!! Form::label('EndTime', 'Endtime:') !!}
    <p>{{ Carbon::parse($payrollSchedules->EndTime)->format('g:i A') }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $payrollSchedules->Notes }}</p>
</div>

