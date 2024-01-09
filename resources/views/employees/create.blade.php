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
                            Step 1
                        </span>
                    </div>

                    {!! Form::open(['route' => 'employees.store']) !!}
        
                    <div class="card-body">
        
                        <div class="row">
                            <!-- ID Field -->
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5">
                                        {!! Form::label('id', 'Employee ID:') !!}
                                    </div>

                                    <div class="col-lg-9 col-md-7">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                            </div>
                                            {!! Form::text('id', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            @include('employees.fields')

                            <p id="Def_Brgy" style="display: none;"></p>

                            <p id="Def_Brgy_Permanent" style="display: none;"></p>
                        </div>
        
                    </div>
        
                    <div class="card-footer">
                        {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('employees.index') }}" class="btn btn-default">Cancel</a>
                    </div>
        
                    {!! Form::close() !!}
        
                </div>
            </div>
        </div>
    </div>
@endsection
