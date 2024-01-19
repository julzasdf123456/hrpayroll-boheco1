@php
    use App\Models\Employees;
@endphp

<!-- Employeeid Field -->
<div class="form-group col-sm-12">
    {!! Form::label('EmployeeId', 'Employee:') !!}
    <select class="custom-select select2"  name="EmployeeId" id="EmployeeId" style="width: 100%;" required>
        <option value="">-- Select --</option>
        @foreach ($employees as $item)
            <option value="{{ $item->id }}" {{ Auth::user()->employee_id==$item->id ? 'selected' : '' }}>{{ Employees::getMergeNameFormal($item) }}</option>
        @endforeach
    </select>
</div>

<!-- Reason Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Reason', 'Reason for Inability to time-in/time-out:') !!}
    {!! Form::text('Reason', null, ['class' => 'form-control', 'maxlength' => 2000, 'maxlength' => 2000, 'required' => true]) !!}
</div>

<!-- Amin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AMIn', 'Morning In:') !!}
    {!! Form::text('AMIn', null, ['class' => 'form-control','id'=>'AMIn']) !!}
</div>


@push('page_scripts')
    <script type="text/javascript">
        $('#AMIn').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Amout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AMOut', 'Morning Out:') !!}
    {!! Form::text('AMOut', null, ['class' => 'form-control','id'=>'AMOut']) !!}
</div>

@push('page_scripts')
<script type="text/javascript">
    $('#AMOut').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: true,
        sideBySide: true
    })
</script>
@endpush

<!-- Pmin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PMIn', 'Afternoon In:') !!}
    {!! Form::text('PMIn', null, ['class' => 'form-control','id'=>'PMIn']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#PMIn').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Pmout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PMOut', 'Afternoon Out:') !!}
    {!! Form::text('PMOut', null, ['class' => 'form-control','id'=>'PMOut']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#PMOut').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Otin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OTIn', 'Overtime In:') !!}
    {!! Form::text('OTIn', null, ['class' => 'form-control','id'=>'OTIn']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#OTIn').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Otout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OTOut', 'Overtime Out:') !!}
    {!! Form::text('OTOut', null, ['class' => 'form-control','id'=>'OTOut']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#OTOut').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

