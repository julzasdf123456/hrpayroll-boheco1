<!-- Loanfor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LoanFor', 'Loanfor:') !!}
    {!! Form::text('LoanFor', null, ['class' => 'form-control', 'maxlength' => 300, 'maxlength' => 300]) !!}
</div>

<!-- Loanname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LoanName', 'Loanname:') !!}
    {!! Form::text('LoanName', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Loandescription Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LoanDescription', 'Loandescription:') !!}
    {!! Form::text('LoanDescription', null, ['class' => 'form-control', 'maxlength' => 3000, 'maxlength' => 3000]) !!}
</div>

<!-- Interestrate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InterestRate', 'Interestrate:') !!}
    {!! Form::number('InterestRate', null, ['class' => 'form-control']) !!}
</div>

<!-- Loanamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LoanAmount', 'Loanamount:') !!}
    {!! Form::number('LoanAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Terms Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Terms', 'Terms:') !!}
    {!! Form::number('Terms', null, ['class' => 'form-control']) !!}
</div>

<!-- Termunit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TermUnit', 'Termunit:') !!}
    {!! Form::text('TermUnit', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Paymentterm Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaymentTerm', 'Paymentterm:') !!}
    {!! Form::text('PaymentTerm', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>