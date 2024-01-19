@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>
                    Attendance Confirmation Form
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3 row">

        @include('adminlte-templates::common.errors')

        <div class="col-lg-6 offset-lg-3 col-md-12">
            <div class="card shadow-none">

                {!! Form::open(['route' => 'attendanceConfirmations.store']) !!}

                <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">
                <input type="hidden" name="Status" value="FILED">
                <input type="hidden" name="UserId" value="{{ Auth::id() }}">

                <div class="card-body">

                    <div class="row">
                        @include('attendane_confirmations.fields')
                    </div>

                </div>

                <div class="card-footer">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('attendanceConfirmations.index') }}" class="btn btn-default"> Cancel </a>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
        
    </div>
@endsection
