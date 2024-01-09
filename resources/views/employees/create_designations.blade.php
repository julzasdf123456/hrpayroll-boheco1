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
                    <h4>Register New Employee</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
            <div class="content px-3">
                @include('adminlte-templates::common.errors')
                <div class="card">

                    <div class="card-header">
                        <span class="card-title">
                            Step 2
                        </span>
                    </div>

                    {!! Form::open(['route' => 'employeesDesignations.store']) !!}

                    <div class="card-body">

                        <div class="row">

                            <div class="col-lg-12">
                                <div class="callout callout-info">
                                    <h5>{{ Employees::getMergeName($employee) }}</h5>
                                </div>
                            </div>

                            <input type="hidden" name="id" id="id" value="{{ IDGenerator::generateID() }}">

                            <input type="hidden" name="EmployeeId" value="{{ $employee->id }}">

                            <input type="hidden" name="IsActive" value="Yes">

                            @include('employees_designations.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('employeesDesignations.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}
        
                </div>
            </div>
        </div>
    </div>
@endsection