@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p><strong>Step 1:</strong> File for Leave Application</p>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">Leave Details</span>
                    </div>
                    {!! Form::open(['route' => 'leaveApplications.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">
                            <input type="hidden" name="EmployeeId" value="{{ Auth::user()->employee_id }}">

                            <input type="hidden" name="Status" value="Filed">

                            @include('leave_applications.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('leaveApplications.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
