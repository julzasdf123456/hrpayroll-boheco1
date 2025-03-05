<!-- Date Field -->
<div class="form-group col-lg-4">
    {!! Form::label('Date', 'Date:') !!}
    {!! Form::text('Date', null, ['class' => 'form-control','id'=>'Date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Date').datepicker({
            format: 'MMMM d, yyyy', // 12-hour time format with AM/PM
            useCurrent: false, // Avoid using the current time as default
            sideBySide: true, // Keep the time selection side by side
            showClear: true, // Optionally, add a clear button
            showClose: true, // Optionally, add a close button    // Add close button
        })
    </script>
@endpush

<!-- Term Field -->
<div class="form-group col-lg-4">
    {!! Form::label('Term', 'Term/Duration:') !!}
    <select name="Term" id="Term" class="form-control">
        <option value="Whole Day">Whole Day</option>
        <option value="Morning Only">Morning Only</option>
        <option value="Afternoon Only">Afternoon Only</option>
    </select>
</div>

<!-- Notes Field -->
<div class="form-group col-lg-4">
    {!! Form::label('Notes', 'Notes/Remarks/Reason of Duty:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1500, 'maxlength' => 1500]) !!}
</div>