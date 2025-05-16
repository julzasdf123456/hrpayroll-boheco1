@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                    Add Biometric Device
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                @include('adminlte-templates::common.errors')

                <div class="card shadow-none">

                    {!! Form::open(['route' => 'biometricDevices.store']) !!}

                    <input type="hidden" name="id" value="{{ IDGenerator::generateIDandRandString() }}">
                    <div class="card-body">

                        <div class="row">
                            @include('biometric_devices.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('biometricDevices.index') }}" class="btn btn-default"> Cancel </a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>        
    </div>
@endsection
