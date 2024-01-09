<!-- Biometricuserid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BiometricUserId', 'Biometricuserid:') !!}
    {!! Form::text('BiometricUserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    {!! Form::text('EmployeeId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Timestamp Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Timestamp', 'Timestamp:') !!}
    {!! Form::text('Timestamp', null, ['class' => 'form-control','id'=>'Timestamp']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Timestamp').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- State Field -->
<div class="form-group col-sm-6">
    {!! Form::label('State', 'State:') !!}
    {!! Form::text('State', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Uid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UID', 'Uid:') !!}
    {!! Form::text('UID', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>