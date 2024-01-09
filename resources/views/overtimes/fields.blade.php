@php
    use App\Models\Employees;
@endphp
<!-- Employeeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EmployeeId', 'Employeeid:') !!}
    <select class="form-control select2" style="width: 100%;" name="EmployeeId" id="EmployeeId">
        @foreach ($employees as $item)
            <option value="{{ $item->id }}">{{ Employees::getMergeName($item) }}</option>
        @endforeach
    </select>
</div>

<!-- Dateofot Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateOfOT', 'Date of Overtime:') !!}
    {!! Form::text('DateOfOT', null, ['class' => 'form-control','id'=>'DateOfOT']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateOfOT').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- From Field -->
<div class="form-group col-sm-4">
    {!! Form::label('From', 'From:') !!}
    {!! Form::text('From', null, ['class' => 'form-control']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#From').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- To Field -->
<div class="form-group col-sm-4">
    {!! Form::label('To', 'To:') !!}
    {!! Form::text('To', null, ['class' => 'form-control']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#To').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- Multiplier Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Multiplier', 'Multiplier:') !!}
    {!! Form::select('Multiplier', ['1.25' => 'Normal OT (x 125%)', '2' => 'Regular Non-Working Holiday (x 200%)', '3' => 'Special Non-Working Holiday (x 300%)'], null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Purpose:') !!}
    {!! Form::textarea('Notes', null, ['class' => 'form-control','maxlength' => 1500,'maxlength' => 1500, 'rows' => 3]) !!}
</div>