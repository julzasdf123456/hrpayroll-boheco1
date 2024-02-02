<!-- Loanid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LoanId', 'Loanid:') !!}
    {!! Form::text('LoanId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Interest Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Interest', 'Interest:') !!}
    {!! Form::number('Interest', null, ['class' => 'form-control']) !!}
</div>

<!-- Principal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Principal', 'Principal:') !!}
    {!! Form::number('Principal', null, ['class' => 'form-control']) !!}
</div>

<!-- Monthlyammortization Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MonthlyAmmortization', 'Monthlyammortization:') !!}
    {!! Form::number('MonthlyAmmortization', null, ['class' => 'form-control']) !!}
</div>

<!-- Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Month', 'Month:') !!}
    {!! Form::text('Month', null, ['class' => 'form-control','id'=>'Month']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Month').datepicker()
    </script>
@endpush

<!-- Paid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Paid', 'Paid:') !!}
    {!! Form::text('Paid', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>