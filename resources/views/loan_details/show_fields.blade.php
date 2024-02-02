<!-- Loanid Field -->
<div class="col-sm-12">
    {!! Form::label('LoanId', 'Loanid:') !!}
    <p>{{ $loanDetails->LoanId }}</p>
</div>

<!-- Interest Field -->
<div class="col-sm-12">
    {!! Form::label('Interest', 'Interest:') !!}
    <p>{{ $loanDetails->Interest }}</p>
</div>

<!-- Principal Field -->
<div class="col-sm-12">
    {!! Form::label('Principal', 'Principal:') !!}
    <p>{{ $loanDetails->Principal }}</p>
</div>

<!-- Monthlyammortization Field -->
<div class="col-sm-12">
    {!! Form::label('MonthlyAmmortization', 'Monthlyammortization:') !!}
    <p>{{ $loanDetails->MonthlyAmmortization }}</p>
</div>

<!-- Month Field -->
<div class="col-sm-12">
    {!! Form::label('Month', 'Month:') !!}
    <p>{{ $loanDetails->Month }}</p>
</div>

<!-- Paid Field -->
<div class="col-sm-12">
    {!! Form::label('Paid', 'Paid:') !!}
    <p>{{ $loanDetails->Paid }}</p>
</div>

