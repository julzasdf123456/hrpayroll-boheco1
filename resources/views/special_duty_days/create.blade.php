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
                    Add Special Duty Date
                    </h4>
                    <span class="text-muted">Could be first Saturday of the month, or something else</span>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'specialDutyDays.store']) !!}
            <input type="hidden" name="id" value="{{ IDGenerator::generateIDandRandString(); }}">
            <div class="card-body">

                <div class="row">
                    @include('special_duty_days.fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('specialDutyDays.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
