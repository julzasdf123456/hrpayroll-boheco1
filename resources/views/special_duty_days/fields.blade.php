<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Date', 'Date:') !!}
    {!! Form::text('Date', null, ['class' => 'form-control','id'=>'Date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Date').datepicker()
    </script>
@endpush

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes/Remarks/Reason of Duty:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1500, 'maxlength' => 1500]) !!}
</div>