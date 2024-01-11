<!-- Holidaydate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('HolidayDate', 'Holiday Date:') !!}
    {!! Form::text('HolidayDate', null, ['class' => 'form-control','id'=>'HolidayDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#HolidayDate').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Holiday Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Holiday', 'Holiday Name:') !!}
    {!! Form::text('Holiday', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Memonumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MemoNumber', 'With Memo Number:') !!}
    {!! Form::text('MemoNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes/Remarks:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>