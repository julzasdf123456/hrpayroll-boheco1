<!-- Employeeid Field -->
<div class="col-sm-12">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    <p>{{ $leaveApplications->EmployeeId }}</p>
</div>

<!-- Datefrom Field -->
<div class="col-sm-12">
    {!! Form::label('DateFrom', 'Datefrom:') !!}
    <p>{{ $leaveApplications->DateFrom }}</p>
</div>

<!-- Dateto Field -->
<div class="col-sm-12">
    {!! Form::label('DateTo', 'Dateto:') !!}
    <p>{{ $leaveApplications->DateTo }}</p>
</div>

<!-- Numberofdays Field -->
<div class="col-sm-12">
    {!! Form::label('NumberOfDays', 'Numberofdays:') !!}
    <p>{{ $leaveApplications->NumberOfDays }}</p>
</div>

<!-- Content Field -->
<div class="col-sm-12">
    {!! Form::label('Content', 'Content:') !!}
    <p>{{ $leaveApplications->Content }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $leaveApplications->Status }}</p>
</div>

