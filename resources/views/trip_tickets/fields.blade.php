<!-- Datetimefiled Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DatetimeFiled', 'Date Filed:') !!}
    {!! Form::text('DatetimeFiled', date('Y-m-d'), ['class' => 'form-control form-control-sm','id'=>'DatetimeFiled']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DatetimeFiled').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true,
            icons : {
                previous : 'fas fa-caret-left',
                next : 'fas fa-caret-right',
            }
        })
    </script>
@endpush

<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control form-control-sm', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Purposeoftravel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PurposeOfTravel', 'Purposeoftravel:') !!}
    {!! Form::text('PurposeOfTravel', null, ['class' => 'form-control form-control-sm', 'maxlength' => 3000, 'maxlength' => 3000]) !!}
</div>

<!-- Driver Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Driver', 'Driver:') !!}
    {!! Form::text('Driver', null, ['class' => 'form-control form-control-sm', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control form-control-sm', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Datetimedeparted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DatetimeDeparted', 'Datetimedeparted:') !!}
    {!! Form::text('DatetimeDeparted', null, ['class' => 'form-control form-control-sm','id'=>'DatetimeDeparted']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DatetimeDeparted').datepicker()
    </script>
@endpush

<!-- Datetimearrived Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DatetimeArrived', 'Datetimearrived:') !!}
    {!! Form::text('DatetimeArrived', null, ['class' => 'form-control form-control-sm','id'=>'DatetimeArrived']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DatetimeArrived').datepicker()
    </script>
@endpush