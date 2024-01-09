@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Create Ranking Repository</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                @include('adminlte-templates::common.errors')

                <div class="card">

                    {!! Form::open(['route' => 'rankingRepositories.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            @include('ranking_repositories.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('rankingRepositories.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
        
    </div>
@endsection
