<!-- Vehiclename Field -->
<div class="col-sm-12">
    {!! Form::label('VehicleName', 'Vehiclename:') !!}
    <p>{{ $vehicles->VehicleName }}</p>
</div>

<!-- Platenumber Field -->
<div class="col-sm-12">
    {!! Form::label('PlateNumber', 'Platenumber:') !!}
    <p>{{ $vehicles->PlateNumber }}</p>
</div>

<!-- Brand Field -->
<div class="col-sm-12">
    {!! Form::label('Brand', 'Brand:') !!}
    <p>{{ $vehicles->Brand }}</p>
</div>

<!-- Model Field -->
<div class="col-sm-12">
    {!! Form::label('Model', 'Model:') !!}
    <p>{{ $vehicles->Model }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $vehicles->Notes }}</p>
</div>

