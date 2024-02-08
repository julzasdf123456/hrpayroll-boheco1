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

<!-- From Field -->
<div class="form-group col-sm-6">
    {!! Form::label('From', 'From:') !!}
    {!! Form::text('From', null, ['class' => 'form-control','id'=>'From']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#From').datepicker()
    </script>
@endpush

<!-- To Field -->
<div class="form-group col-sm-6">
    {!! Form::label('To', 'To:') !!}
    {!! Form::text('To', null, ['class' => 'form-control','id'=>'To']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#To').datepicker()
    </script>
@endpush

<!-- Totalhoursrendered Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalHoursRendered', 'Totalhoursrendered:') !!}
    {!! Form::number('TotalHoursRendered', null, ['class' => 'form-control']) !!}
</div>

<!-- Totalworkedhours Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalWorkedHours', 'Totalworkedhours:') !!}
    {!! Form::number('TotalWorkedHours', null, ['class' => 'form-control']) !!}
</div>

<!-- Monthlywage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MonthlyWage', 'Monthlywage:') !!}
    {!! Form::number('MonthlyWage', null, ['class' => 'form-control']) !!}
</div>

<!-- Termwage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TermWage', 'Termwage:') !!}
    {!! Form::number('TermWage', null, ['class' => 'form-control']) !!}
</div>

<!-- Overtimehours Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OvertimeHours', 'Overtimehours:') !!}
    {!! Form::number('OvertimeHours', null, ['class' => 'form-control']) !!}
</div>

<!-- Overtimeamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OvertimeAmount', 'Overtimeamount:') !!}
    {!! Form::number('OvertimeAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Absenthours Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AbsentHours', 'Absenthours:') !!}
    {!! Form::number('AbsentHours', null, ['class' => 'form-control']) !!}
</div>

<!-- Absentamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AbsentAmount', 'Absentamount:') !!}
    {!! Form::number('AbsentAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Longevity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Longevity', 'Longevity:') !!}
    {!! Form::number('Longevity', null, ['class' => 'form-control']) !!}
</div>

<!-- Ricelaundry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RiceLaundry', 'Ricelaundry:') !!}
    {!! Form::number('RiceLaundry', null, ['class' => 'form-control']) !!}
</div>

<!-- Othersalaryadditions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherSalaryAdditions', 'Othersalaryadditions:') !!}
    {!! Form::number('OtherSalaryAdditions', null, ['class' => 'form-control']) !!}
</div>

<!-- Othersalarydeductions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherSalaryDeductions', 'Othersalarydeductions:') !!}
    {!! Form::number('OtherSalaryDeductions', null, ['class' => 'form-control']) !!}
</div>

<!-- Totalpartialamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalPartialAmount', 'Totalpartialamount:') !!}
    {!! Form::number('TotalPartialAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Motorycleloan Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MotorycleLoan', 'Motorycleloan:') !!}
    {!! Form::number('MotorycleLoan', null, ['class' => 'form-control']) !!}
</div>

<!-- Pagibigcontribution Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PagIbigContribution', 'Pagibigcontribution:') !!}
    {!! Form::number('PagIbigContribution', null, ['class' => 'form-control']) !!}
</div>

<!-- Pagibigloan Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PagIbigLoan', 'Pagibigloan:') !!}
    {!! Form::number('PagIbigLoan', null, ['class' => 'form-control']) !!}
</div>

<!-- Ssscontribution Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SSSContribution', 'Ssscontribution:') !!}
    {!! Form::number('SSSContribution', null, ['class' => 'form-control']) !!}
</div>

<!-- Sssloan Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SSSLoan', 'Sssloan:') !!}
    {!! Form::number('SSSLoan', null, ['class' => 'form-control']) !!}
</div>

<!-- Philhealthcontribution Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PhilHealthContribution', 'Philhealthcontribution:') !!}
    {!! Form::number('PhilHealthContribution', null, ['class' => 'form-control']) !!}
</div>

<!-- Otherdeductions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherDeductions', 'Otherdeductions:') !!}
    {!! Form::number('OtherDeductions', null, ['class' => 'form-control']) !!}
</div>

<!-- Salarywithholdingtax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SalaryWithholdingTax', 'Salarywithholdingtax:') !!}
    {!! Form::number('SalaryWithholdingTax', null, ['class' => 'form-control']) !!}
</div>

<!-- Totalwithholdingtax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalWithholdingTax', 'Totalwithholdingtax:') !!}
    {!! Form::number('TotalWithholdingTax', null, ['class' => 'form-control']) !!}
</div>

<!-- Totaldeductions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalDeductions', 'Totaldeductions:') !!}
    {!! Form::number('TotalDeductions', null, ['class' => 'form-control']) !!}
</div>

<!-- Netpay Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetPay', 'Netpay:') !!}
    {!! Form::number('NetPay', null, ['class' => 'form-control']) !!}
</div>

<!-- Generatedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GeneratedBy', 'Generatedby:') !!}
    {!! Form::text('GeneratedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Auditedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AuditedBy', 'Auditedby:') !!}
    {!! Form::text('AuditedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Checkedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckedBy', 'Checkedby:') !!}
    {!! Form::text('CheckedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Approvedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ApprovedBy', 'Approvedby:') !!}
    {!! Form::text('ApprovedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Generateddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GeneratedDate', 'Generateddate:') !!}
    {!! Form::text('GeneratedDate', null, ['class' => 'form-control','id'=>'GeneratedDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#GeneratedDate').datepicker()
    </script>
@endpush

<!-- Auditeddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AuditedDate', 'Auditeddate:') !!}
    {!! Form::text('AuditedDate', null, ['class' => 'form-control','id'=>'AuditedDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#AuditedDate').datepicker()
    </script>
@endpush

<!-- Checkeddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckedDate', 'Checkeddate:') !!}
    {!! Form::text('CheckedDate', null, ['class' => 'form-control','id'=>'CheckedDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#CheckedDate').datepicker()
    </script>
@endpush

<!-- Approveddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ApprovedDate', 'Approveddate:') !!}
    {!! Form::text('ApprovedDate', null, ['class' => 'form-control','id'=>'ApprovedDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ApprovedDate').datepicker()
    </script>
@endpush

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 2000, 'maxlength' => 2000]) !!}
</div>