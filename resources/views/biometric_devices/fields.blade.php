<!-- Ipaddress Field -->
<div class="form-group col-sm-12">
    {!! Form::label('IPAddress', 'IP Address:') !!}
    {!! Form::text('IPAddress', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50, 'autofocus' => true]) !!}
</div>

<!-- Brand Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Brand', 'Brand:') !!}
    {!! Form::text('Brand', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Office Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Office', 'Office Assigned/Placed:') !!}
    {!! Form::text('Office', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <select name="Status" class="form-control">
        <option value="ACTIVE">ACTIVE</option>
        <option value="INACTIVE">INACTIVE</option>
    </select>
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notes/Remarks:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>