@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Employees Details</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('employees.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img id="prof-img" class="profile-user-img img-fluid img-circle" src="" alt="User profile picture">
                        </div>
      
                        <h3 class="profile-username text-center">
                            {{ Employees::getMergeName($employees) }}
                            @canany('god permission', 'employees update')
                            <a title="Change picture" href="{{ route('employees.capture-image', [$employees->id]) }}" style="padding-left: 10px;"><i class="fas fa-camera text-success" style="font-size: .8em;"></i></a>
                            @endcanany
                        </h3>
      
                        <p class="text-muted text-center">
                            @if (count($employeeDesignations) > 0)
                                {{ $employeeDesignations{0}->Position }}
                            @endif
                        </p>
      
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Contact</b> <a class="float-right">{{ $employees->ContactNumbers }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ $employees->EmailAddress }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Job Status</b> <a class="float-right">
                                    @if (count($employeeDesignations) > 0)
                                        {{ $employeeDesignations{0}->Status }}
                                    @endif
                                </a>
                            </li>
                        </ul>
                        
                        @canany('god permission', 'employees update')
                        <a href="{{ route('employees.edit', [$employees->id]) }}" class="btn btn-link text-info" title="Edit {{ Employees::getMergeName($employees) }}"><i class="fas fa-pen"></i></a>
                        @if (count($employeeDesignations) > 0)
                            <a href="{{ route('employeesDesignations.edit', [$employeeDesignations[0]->id]) }}" class="btn btn-link text-info" title="Edit {{ Employees::getMergeName($employees) }}'s Job Status"><i class="fas fa-clipboard"></i></a>
                            <a href="{{ route('employees.create-designations', [$employees->id]) }}" class="btn btn-link text-warning" title="Promote {{ Employees::getMergeName($employees) }}"><i class="fas fa-hand-point-up"></i></a>
                        @else
                            <a href="{{ route('employees.create-designations', [$employees->id]) }}" class="btn btn-link text-success" title="Add job description to {{ Employees::getMergeName($employees) }}"><i class="fas fa-folder-plus"></i></a>
                        @endif
                        @endcanany

                        <a href="{{ route('employees.attendance', [$employees->id]) }}" class="btn btn-link text-info" title="View attendance"><i class="fas fa-calendar-alt"></i></a>
                        
                        @canany('god permission', 'employees delete')
                        {!! Form::open(['route' => ['employees.destroy', $employees->id], 'method' => 'delete', 'style' => 'display: inline;']) !!}
                            {!! Form::button('<i class="fas fa-trash-alt" title="Delete this employee"></i>', ['type' => 'submit', 'class' => 'btn btn-link text-danger float-right', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        {!! Form::close() !!}
                        @endcanany
                    </div>
                    <!-- /.card-body -->
                </div>

                <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">About Me</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">        
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Current Address</strong>        
                        <p class="text-muted">{{ Employees::getCurrentAddress($employees) }}</p>
        
                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Permanent Address</strong>        
                        <p class="text-muted">{{ Employees::getPermanentAddress($employees) }}</p>
        
                        <hr>
        
                        <strong><i class="fas fa-birthday-cake mr-1"></i> Birthday</strong>
        
                        <p class="text-muted">
                            @if ($employees->Birthdate != null)
                                {{ date('F d, Y', strtotime($employees->Birthdate)) }}
                            @endif
                        </p>
        
                        <hr>

                        <strong><i class="fas fa-info-circle mr-1"></i> Civil Status</strong>        
                        <p class="text-muted">{{ $employees->CivilStatus }}</p>
        
                        <hr>

                        <strong><i class="fas fa-globe-asia mr-1"></i> Citizenship</strong>        
                        <p class="text-muted">{{ $employees->Citizenship }}</p>
        
                        <hr>

                        <strong><i class="fas fa-cross mr-1"></i> Religion</strong>        
                        <p class="text-muted">{{ $employees->Religion }}</p>
        
                        <hr>

                        <strong><i class="fas fa-pump-medical mr-1"></i> Blood Type</strong>        
                        <p class="text-muted">{{ $employees->BloodType }}</p>
        
                        <hr>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>

            <div class="col-lg-8 col-md-7">
                <div class="card card-primary card-outline card-tabs shadow-none">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="dtr-tab" data-toggle="pill" href="#dtr-content" role="tab" aria-controls="dtr-content" aria-selected="false">DTR</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="leave-tab" data-toggle="pill" href="#leave-content" role="tab" aria-controls="leave-content" aria-selected="false">Leave</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="trip-tickets-tab" data-toggle="pill" href="#trip-ticket-content" role="tab" aria-controls="trip-ticket-content" aria-selected="false">Trip Tickets</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="promotions-tab" data-toggle="pill" href="#promotions-content" role="tab" aria-controls="promotions-content" aria-selected="false">Promotions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="educational-attainments-tab" data-toggle="pill" href="#educational-attainment-content" role="tab" aria-controls="educational-attainment-content" aria-selected="false">Educational Attainment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="private-info-tab" data-toggle="pill" href="#private-info-content" role="tab" aria-controls="private-info-content" aria-selected="false">Private Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="payslip-tab" data-toggle="pill" href="#payslip-content" role="tab" aria-controls="payslip-content" aria-selected="false">Payslips</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade active show" id="dtr-content" role="tabpanel" aria-labelledby="dtr-tab">
                                @include('employees.dtr_view')
                            </div>
                            <div class="tab-pane fade" id="leave-content" role="tabpanel" aria-labelledby="leave-tab">
                                @include('employees.leave')
                            </div>
                            <div class="tab-pane fade" id="trip-ticket-content" role="tabpanel" aria-labelledby="trip-tickets-tab">
                                @include('employees.tab_trip_tickets')
                            </div>
                            <div class="tab-pane fade" id="promotions-content" role="tabpanel" aria-labelledby="promotions-tab">
                                @include('employees.promotions')
                            </div>
                            <div class="tab-pane fade" id="educational-attainment-content" role="tabpanel" aria-labelledby="educational-attainments-tab">
                                @include('employees.educational_attainment_view')
                            </div>
                            <div class="tab-pane fade" id="private-info-content" role="tabpanel" aria-labelledby="private-info-tab">
                                @include('employees.private_info_view')
                            </div>
                            <div class="tab-pane fade" id="payslip-content" role="tabpanel" aria-labelledby="payslip-tab">
                                @include('employees.payslips')
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // LOAD IMAGE
            $.ajax({
                url : '/employees/get-image/' + "{{ $employees->id }}",
                type : 'GET',
                success : function(result) {
                    var data = JSON.parse(result)
                    $('#prof-img').attr('src', data['img'])
                },
                error : function(error) {
                    console.log(error);
                }
            })
        });
    </script>
@endpush
