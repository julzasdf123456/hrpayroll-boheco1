<!-- Travelorderid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TravelOrderId', 'Travelorderid:') !!}
    {!! Form::text('TravelOrderId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Day Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Day', 'Day:') !!}
    {!! Form::text('Day', null, ['class' => 'form-control','id'=>'Day']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Day').datepicker()
    </script>
@endpush

<!-- Longevity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Longevity', 'Longevity:') !!}
    {!! Form::text('Longevity', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>