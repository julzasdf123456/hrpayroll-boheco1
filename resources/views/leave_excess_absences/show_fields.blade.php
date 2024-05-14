<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $leaveExcessAbsences->id }}</p>
</div>

<!-- Employeeid Field -->
<div class="col-sm-12">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    <p>{{ $leaveExcessAbsences->EmployeeId }}</p>
</div>

<!-- Leavedate Field -->
<div class="col-sm-12">
    {!! Form::label('LeaveDate', 'Leavedate:') !!}
    <p>{{ $leaveExcessAbsences->LeaveDate }}</p>
</div>

<!-- Hoursabsent Field -->
<div class="col-sm-12">
    {!! Form::label('HoursAbsent', 'Hoursabsent:') !!}
    <p>{{ $leaveExcessAbsences->HoursAbsent }}</p>
</div>

