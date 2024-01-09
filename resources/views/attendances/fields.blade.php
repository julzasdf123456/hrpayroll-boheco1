<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60]) !!}
</div>

<!-- Morningtimein Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MorningTimeIn', 'Morningtimein:') !!}
    {!! Form::text('MorningTimeIn', null, ['class' => 'form-control','id'=>'MorningTimeIn']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#MorningTimeIn').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Morningtimeout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MorningTimeOut', 'Morningtimeout:') !!}
    {!! Form::text('MorningTimeOut', null, ['class' => 'form-control','id'=>'MorningTimeOut']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#MorningTimeOut').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Afternoontimein Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AfternoonTimeIn', 'Afternoontimein:') !!}
    {!! Form::text('AfternoonTimeIn', null, ['class' => 'form-control','id'=>'AfternoonTimeIn']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#AfternoonTimeIn').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Afternoontimeout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AfternoonTimeOut', 'Afternoontimeout:') !!}
    {!! Form::text('AfternoonTimeOut', null, ['class' => 'form-control','id'=>'AfternoonTimeOut']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#AfternoonTimeOut').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Ottimein Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OTTimeIn', 'Ottimein:') !!}
    {!! Form::text('OTTimeIn', null, ['class' => 'form-control','id'=>'OTTimeIn']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#OTTimeIn').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Ottimeout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OTTimeOut', 'Ottimeout:') !!}
    {!! Form::text('OTTimeOut', null, ['class' => 'form-control','id'=>'OTTimeOut']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#OTTimeOut').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush