<!-- Datefrom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateFrom', 'Datefrom:') !!}
    {!! Form::text('DateFrom', null, ['class' => 'form-control','id'=>'DateFrom']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateFrom').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Dateto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateTo', 'Dateto:') !!}
    {!! Form::text('DateTo', null, ['class' => 'form-control','id'=>'DateTo']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateTo').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Employeetype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeType', 'Employeetype:') !!}
    {!! Form::text('EmployeeType', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>