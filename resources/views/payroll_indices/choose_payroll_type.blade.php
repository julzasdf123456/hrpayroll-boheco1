@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Generate Payroll</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        <div class="col-lg-4 offset-lg-4 col-md-6 offset-lg-3">
            <div class="card">
                {!! Form::open(['route' => 'payrollIndices.validate-payroll-select-type']) !!}
                <div class="card-header">
                    <span class="card-title">Setup Payroll</span>
                </div>
                <div class="card-body">
                    <div class="form-group col-sm-12">
                        <label>Select Employees</label>
                        @foreach ($type as $item)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-{{ $item->Status }}" value="{{ $item->Status }}">
                                <label class="form-check-label" for="status-{{ $item->Status }}">{{ $item->Status }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group col-sm-12">
                        <label>Select Department</label>
                        <select name="Department" class="form-control">
                        @foreach ($departments as $item)
                            <option value="{{ $item->Department }}">{{ $item->Department }}</option>
                        @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('SalaryPeriod', 'Salary Period:') !!}
                        @php
                            $currentMid = date('Y-m-') . '15';
                            $currentEnd = date('Y-m-') . '30';
                        @endphp
                        <select name="SalaryPeriod" id="SalaryPeriod" class="form-control">
                            <option value="{{ $currentMid }}">{{ date('F d, Y', strtotime($currentMid)) }}</option>
                            <option value="{{ $currentEnd }}">{{ date('F d, Y', strtotime($currentEnd)) }}</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('DateFrom', 'From:') !!}
                        {!! Form::text('DateFrom', null, ['class' => 'form-control','id'=>'DateFrom']) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#DateFrom').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-sm-12">
                        {!! Form::label('DateTo', 'To:') !!}
                        {!! Form::text('DateTo', null, ['class' => 'form-control','id'=>'DateTo']) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#DateTo').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush
                </div>
                <div class="card-footer">
                    {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection