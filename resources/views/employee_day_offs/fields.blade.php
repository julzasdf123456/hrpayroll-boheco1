<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Dayoff Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DayOff', 'Dayoff:') !!}
    {!! Form::text('DayOff', null, ['class' => 'form-control','id'=>'DayOff']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DayOff').datepicker()
    </script>
@endpush

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>