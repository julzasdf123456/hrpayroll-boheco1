@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Employees</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($employees, ['route' => ['employees.update', $employees->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    @include('employees.fields')

                    <p id="Def_Brgy" style="display: none;">{{ $employees->BarangayCurrent }}</p>

                    <p id="Def_Brgy_Permanent" style="display: none;">{{ $employees->BarangayPermanent }}</p>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('employees.index') }}" class="btn btn-default">Cancel</a>
            </div>

           {!! Form::close() !!}

        </div>
    </div>
@endsection
