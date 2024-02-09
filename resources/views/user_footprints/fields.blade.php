<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Logname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LogName', 'Logname:') !!}
    {!! Form::text('LogName', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Logdetails Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LogDetails', 'Logdetails:') !!}
    {!! Form::text('LogDetails', null, ['class' => 'form-control', 'maxlength' => 1500, 'maxlength' => 1500]) !!}
</div>

<!-- Computername Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ComputerName', 'Computername:') !!}
    {!! Form::text('ComputerName', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>