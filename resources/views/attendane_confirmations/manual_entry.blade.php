@php
    use App\Models\Employees;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4><strong>MANUAL ENTRY: </strong> Attendance Confirmation</h4>
            </div>
        </div>
    </div>
</section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')
        @include('flash::message')

        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <div class="card shadow-none">

                    {!! Form::open(['route' => 'attendanceConfirmations.save-manual-entry']) !!}

                    <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">
                    <input type="hidden" name="Status" value="APPROVED">
                    <input type="hidden" name="UserId" value="{{ Auth::id() }}">
        
                    <div class="card-body">
        
                        <div class="row">
                            
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
                        </div>
        
                    </div>
        
                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('employeePayrollSchedules.index') }}" class="btn btn-default">Cancel</a>
                    </div>
        
                    {!! Form::close() !!}
        
                </div>
            </div>
        </div>
    </div>
@endsection
