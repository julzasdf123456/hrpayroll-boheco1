@php
    use App\Models\Employees;
@endphp

<!-- Vehiclename Field -->
<div class="form-group col-sm-6">
    {!! Form::label('VehicleName', 'Vehicle Name:') !!}
    {!! Form::text('VehicleName', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Platenumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PlateNumber', 'Plate Number:') !!}
    {!! Form::text('PlateNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Brand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Brand', 'Brand:') !!}
    {!! Form::text('Brand', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Model Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Model', 'Model:') !!}
    {!! Form::text('Model', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Model Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DesignatedDriver', 'Designated Driver:') !!}
    <select class="custom-select select2" name="DesignatedDriver" id="DesignatedDriver">
        <option value="">-- Select --</option>
        @foreach ($drivers as $item)
            <option value="{{ $item->id }}" {{ $vehicles != null && $vehicles->DesignatedDriver==$item->id ? 'selected' : '' }}>{{ Employees::getMergeNameFormal($item) }}</option>
        @endforeach
    </select>
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes/Remarks:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>