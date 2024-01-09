<!-- Employeeid Field -->
<div class="col-sm-12">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    <p>{{ $leaveBalanceDetails->EmployeeId }}</p>
</div>

<!-- Method Field -->
<div class="col-sm-12">
    {!! Form::label('Method', 'Method:') !!}
    <p>{{ $leaveBalanceDetails->Method }}</p>
</div>

<!-- Days Field -->
<div class="col-sm-12">
    {!! Form::label('Days', 'Days:') !!}
    <p>{{ $leaveBalanceDetails->Days }}</p>
</div>

<!-- Details Field -->
<div class="col-sm-12">
    {!! Form::label('Details', 'Details:') !!}
    <p>{{ $leaveBalanceDetails->Details }}</p>
</div>

