@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Create Information for Everyone</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')
        <form class="row" method="POST" action="{{ route('notifications.store') }}">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            {{-- compose --}}
            <div class="col-lg-7 col-md-12">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="row">
                            @include('notifications.fields')
                        </div>
                    </div>
                </div>
            </div>
            {{-- config --}}
            <div class="col-lg-5 col-md-12">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">
                            Configuration
                        </span>
                    </div>
                    <div class="card-body">
                        <span class="text-muted">SMS Notifications</span>
                        <div class="form-group custom-control custom-switch mt-2 ml-4">
                            <input type="checkbox" class="custom-control-input" name="SendSms" id="SendSms" value="SendSms">
                            <label class="custom-control-label" for="SendSms" id="SendSmsLabel">Also Send SMS</label>
                        </div>

                        <span class="text-muted">Select Audience/Department</span>
                        <div class="form-checkbox mt-2 ml-4">
                            <label>
                                <input type="checkbox" name="Department[]" value="ESD">
                                ESD
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="Department[]" value="ISD">
                                ISD
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="Department[]" value="OGM">
                                OGM
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="Department[]" value="OSD">
                                OSD
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="Department[]" value="PGD">
                                PGD
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="Department[]" value="SEEAD">
                                SEEAD
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="Department[]" value="SUB-OFFICE">
                                SUB-OFFICE
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary float-right" type="submit">Send Notifications <i class="fas fa-paper-plane ico-tab-left-mini"></i></button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection
