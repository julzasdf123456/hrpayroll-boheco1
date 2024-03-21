<!-- Datefiled Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateFiled', 'Datefiled:') !!}
    {!! Form::text('DateFiled', null, ['class' => 'form-control','id'=>'DateFiled']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateFiled').datepicker()
    </script>
@endpush

<!-- Destination Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Destination', 'Destination:') !!}
    {!! Form::text('Destination', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Purpose Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Purpose', 'Purpose:') !!}
    {!! Form::text('Purpose', null, ['class' => 'form-control', 'maxlength' => 2000, 'maxlength' => 2000]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>