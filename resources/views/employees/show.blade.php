@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;
@endphp
@extends('layouts.app')

@section('content')
    <meta name="view-employee-id" content="{{ $employees->id }}">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                {{-- EMPLOYEE PROFILE TOP --}}
                <div class="col-lg-8">
                    <div style="display: flex; padding-top: 15px; padding-bottom: 15px;">
                        <div style="width: 88px; display: inline;">
                            @if ($employees->ProfilePicture != null)
                                <img id="prof-img" style="width: 75px !important; height: 75px !important;" class="profile-user-img img-fluid img-circle img-cover" src="{{ asset('imgs/profiles/') . "/" . $employees->ProfilePicture }}" alt="User profile picture">
                            @else
                                <img id="prof-img" style="width: 75px !important; height: 75px !important;" class="profile-user-img img-fluid img-circle img-cover" src="{{ asset('imgs/prof-img.png') }}" alt="User profile picture">
                            @endif
                            
                        </div>
                        <div>
                            <span>
                                <span style="font-size: 1.85em;"><strong>{{ Employees::getMergeName($employees) }}</strong></span>
                                @if (in_array($employees->EmploymentStatus, ['Resigned', 'Retired']))
                                    <span class="badge bg-danger" style="font-size: 1em;">{{ $employees->EmploymentStatus }}</span>
                                @endif
                                <br>
                                <span class="text-muted">
                                    @if (count($employeeDesignations) > 0)
                                        <i class="fas fa-lightbulb ico-tab-mini"></i>{{ $employeeDesignations{0}->Position }}
                                    @endif

                                    @if ($employees->ContactNumbers != null)
                                        <span style="margin-left: 15px; margin-right: 15px;">|</span><i class="fas fa-phone ico-tab-mini"></i>{{ $employees->ContactNumbers }}
                                    @endif

                                    @if ($employees->PositionStatus != null)
                                        <span style="margin-left: 15px; margin-right: 15px;">|</span><i class="fas fa-info-circle ico-tab-mini"></i>{{ $employees->PositionStatus }}
                                    @endif
                                </span>
                            </span>
                        </div>
                    </div>
                    
                </div>
                {{-- ACTIONS --}}
                <div class="col-lg-4">  
                    @canany('god permission', 'employees delete', 'create payroll')
                        @if ($payrollSundries == null)
                            <button onclick="showPayrollSundriesConfig()" class="btn btn-primary float-right {{ $colorProf != null ? 'text-white' : '' }}" style="margin-left: 15px;">Configure Payroll Deductions/Sundries</button>
                        @endif
                    @endcanany

                    @canany('god permission', 'employees delete')
                        @if (count($employeeDesignations) < 1)
                            <a href="{{ route('employees.create-designations', [$employees->id]) }}" class="btn btn-primary float-right {{ $colorProf != null ? 'text-white' : '' }}" style="margin-left: 15px;" title="Add job description to {{ Employees::getMergeName($employees) }}">Add Position</a>
                        @endif
                    @endcanany

                    <div class="dropdown">
                        <a class="btn btn-primary-skinny dropdown-toggle float-right {{ $colorProf != null ? 'text-white' : '' }}" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="margin-right: 15px;">
                            Actions
                        </a>

                        <div class="dropdown-menu">
                            @canany('god permission', 'employees update')
                                <a class="dropdown-item" href="{{ route('employees.edit', [$employees->id]) }}"><i class="fas fa-pen ico-tab"></i>Edit Details</a>
                                @if (count($employeeDesignations) > 0)
                                    <a class="dropdown-item" href="{{ route('employeesDesignations.edit', [$employeeDesignations[0]->id]) }}"><i class="fas fa-lightbulb ico-tab"></i>Edit Position</a>
                                    <a class="dropdown-item" href="{{ route('employees.create-designations', [$employees->id]) }}"><i class="fas fa-hand-point-up ico-tab"></i>Create Promotion</a>
                                @endif
                                <div class="dropdown-divider"></div>
                                {{-- ALLOW NO ATTENDANCE --}}
                                @if ($employees->NoAttendanceAllowed != null)
                                    <button class="dropdown-item" onclick="disAllowNoAttendance(`{{ $employees->id }}`)"><i class="fas fa-times ico-tab"></i>Disable No Attendance</button>
                                @else
                                    <button class="dropdown-item" onclick="allowNoAttendance(`{{ $employees->id }}`)"><i class="fas fa-fingerprint ico-tab"></i>Allow No Attendance</button>
                                @endif
                                {{-- ALLOW FILING OF LEAVE FOR OTHER PEOPLE --}}
                                @if ($userData != null)
                                    <a class="dropdown-item" href="{{ route('leaveUsersForOthers.configure', [$userData->id]) }}"><i class="fas fa-share-alt ico-tab"></i>Configure Filing for Others</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('employees.upload-file', [$employees->id]) }}"><i class="fas fa-upload ico-tab"></i>Upload Files and Docs</a>
                            @endcanany
                            <a class="dropdown-item" href="{{ route('employees.attendance', [$employees->id]) }}"><i class="fas fa-calendar-alt ico-tab"></i>View Attendance</a>

                            @canany('god permission', 'employees delete', 'create payroll')
                                @if ($payrollSundries != null)
                                    <button onclick="showPayrollSundriesConfig()" class="dropdown-item"><i class="fas fa-receipt ico-tab"></i>Update Payroll Deductions/Sundries</button>
                                @endif
                            @endcanany
                            
                            @canany('god permission', 'employees delete')
                            <div class="dropdown-divider"></div>
                            @if (!in_array($employees->EmploymentStatus, ['Resigned', 'Retired']))
                                <button onclick="retire()" class="dropdown-item"><i class="fas fa-stop-circle ico-tab"></i>Retire/Resign</button>
                            @endif                            
                            {!! Form::open(['route' => ['employees.destroy', $employees->id], 'method' => 'delete', 'style' => 'display: inline;']) !!}
                                {!! Form::button('<i class="fas fa-trash-alt ico-tab text-danger" title="Delete this employee"></i>Trash Employee Data', ['type' => 'submit', 'class' => 'dropdown-item text-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            {!! Form::close() !!}
                            @endcanany
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-none">
                    <div class="card-body p-0">
                        <div class="row">
                            {{-- BASIC DETAILS --}}
                            <div class="col-lg-4" style="padding: 25px 35px 15px 35px;">
                                <h4><strong>Key Details</strong></h4>

                                <table class="table table-sm table-borderless" style="margin-top: 18px;">
                                    <tbody>
                                        <tr>
                                            <td><i class="fas text-muted fa-venus-mars"></i></td>
                                            <td>{{ $employees->Gender }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas text-muted fa-at"></i></td>
                                            <td>{{ $employees->EmailAddress }}</td>
                                        </tr>
                                        <tr title="Current Address">
                                            <td><i class="fas text-muted fa-map-pin"></i></td>
                                            <td>{{ Employees::getCurrentAddress($employees) }}</td>
                                        </tr>
                                        <tr title="Permanent Address">
                                            <td><i class="fas text-muted fa-map-marker-alt"></i></td>
                                            <td>{{ Employees::getPermanentAddress($employees) }}</td>
                                        </tr>
                                        <tr title="Birthday">
                                            <td><i class="fas text-muted fa-birthday-cake"></i></td>
                                            <td>{{ $employees->Birthdate != null ? date('F d, Y', strtotime($employees->Birthdate)) : '' }}</td>
                                        </tr>
                                        <tr title="Civil Status">
                                            <td><i class="fas text-muted fa-paperclip"></i></td>
                                            <td>{{ $employees->CivilStatus }}</td>
                                        </tr>
                                        <tr title="Citizenship">
                                            <td><i class="fas text-muted fa-flag"></i></td>
                                            <td>{{ $employees->Citizenship }}</td>
                                        </tr>
                                        <tr title="Religion">
                                            <td><i class="fas text-muted fa-cross"></i></td>
                                            <td>{{ $employees->Religion }}</td>
                                        </tr>
                                        <tr title="Blood Type">
                                            <td><i class="fas text-muted fa-tint"></i></td>
                                            <td>{{ $employees->BloodType }}</td>
                                        </tr>
                                        <tr title="Date Hired">
                                            <td><i class="fas text-muted fa-user-md"></i></td>
                                            <td>
                                                {{ $employees->DateHired != null ? date('F d, Y', strtotime($employees->DateHired)) : '-' }} 
                                                @if ($employees->DateHired != null)
                                                    ({{ Employees::getYearsFromDateHired($employees->DateHired) }} years)
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- TABS --}}
                            <div class="col-lg-8 {{ $colorProf != null ? 'bl-dark' : 'bl-light' }}" style="padding-top: 15px; padding-bottom: 15px; padding-left: 25px; padding-right: 25px;">
                                {{-- TAB HEADS --}}
                                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="dtr-tab" data-toggle="pill" href="#dtr-content" role="tab" aria-controls="dtr-content" aria-selected="false">DTR</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="leave-tab" data-toggle="pill" href="#leave-content" role="tab" aria-controls="leave-content" aria-selected="false">Leave</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="offset-tab" data-toggle="pill" href="#offset-content" role="tab" aria-controls="offset-content" aria-selected="false">Offsets</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="attendance-confirmations-tab" data-toggle="pill" href="#attendance-confirmations-content" role="tab" aria-controls="attendance-confirmations-content" aria-selected="false">Att. Confirmations</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="trip-tickets-tab" data-toggle="pill" href="#trip-ticket-content" role="tab" aria-controls="trip-ticket-content" aria-selected="false">Trip Tickets</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="travel-orders-tab" data-toggle="pill" href="#travel-order-content" role="tab" aria-controls="travel-order-content" aria-selected="false">Travel Orders</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="overtime-tab" data-toggle="pill" href="#overtime-content" role="tab" aria-controls="overtime-content" aria-selected="false">Overtime</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="promotions-tab" data-toggle="pill" href="#promotions-content" role="tab" aria-controls="promotions-content" aria-selected="false">Promotions</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="files-tab" data-toggle="pill" href="#files-content" role="tab" aria-controls="files-content" aria-selected="false">Files & Docs</a>
                                    </li>
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" id="private-info-tab" data-toggle="pill" href="#private-info-content" role="tab" aria-controls="private-info-content" aria-selected="false">Private Information</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="payslip-tab" data-toggle="pill" href="#payslip-content" role="tab" aria-controls="payslip-content" aria-selected="false">Payslips</a>
                                    </li> --}}
                                </ul>
                                {{-- TAB BODY --}}
                                <div class="tab-content" id="custom-tabs-three-tabContent">
                                    <div class="tab-pane fade active show" id="dtr-content" role="tabpanel" aria-labelledby="dtr-tab">
                                        @include('employees.dtr_view')
                                    </div>
                                    <div class="tab-pane fade" id="leave-content" role="tabpanel" aria-labelledby="leave-tab">
                                        @include('employees.leave')
                                    </div>
                                    <div class="tab-pane fade" id="offset-content" role="tabpanel" aria-labelledby="offset-tab">
                                        @include('employees.tab_offsets')
                                    </div>
                                    <div class="tab-pane fade" id="attendance-confirmations-content" role="tabpanel" aria-labelledby="attendance-confirmations-tab">
                                        @include('employees.tab_attendance_confirmations')
                                    </div>
                                    <div class="tab-pane fade" id="trip-ticket-content" role="tabpanel" aria-labelledby="trip-tickets-tab">
                                        @include('employees.tab_trip_tickets')
                                    </div>
                                    <div class="tab-pane fade" id="travel-order-content" role="tabpanel" aria-labelledby="travel-orders-tab">
                                        @include('employees.tab_travel_orders')
                                    </div>
                                    <div class="tab-pane fade" id="overtime-content" role="tabpanel" aria-labelledby="overtime-tab">
                                        @include('employees.tab_overtime')
                                    </div>
                                    <div class="tab-pane fade" id="promotions-content" role="tabpanel" aria-labelledby="promotions-tab">
                                        @include('employees.promotions')
                                    </div>
                                    <div class="tab-pane fade" id="files-content" role="tabpanel" aria-labelledby="files-tab">
                                        @include('employees.tab_files')
                                    </div>
                                    {{-- <div class="tab-pane fade" id="private-info-content" role="tabpanel" aria-labelledby="private-info-tab">
                                        @include('employees.private_info_view')
                                    </div>
                                    <div class="tab-pane fade" id="payslip-content" role="tabpanel" aria-labelledby="payslip-tab">
                                        @include('employees.payslips')
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('employee_payroll_sundries.modal_configure_payroll_sundries')

@include('employees.modal_retire')

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
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

            // RESIGN/RETIRE BUTTON
            $('#submit-retire').on('click', function(e) {
                e.preventDefault()

                var type = $('#end-type').val()
                var effectiveDate = $('#effective-date').val()

                if (isNull(type) | isNull(effectiveDate)) {
                    Toast.fire({
                        icon : 'info',
                        text : 'Please fill in all fields!'
                    })
                } else {
                    $.ajax({
                        url : "{{ route('employees.update-end') }}",
                        type : "GET",
                        data : {
                            id : "{{ $employees->id }}",
                            Type : type,
                            DateEnded : effectiveDate,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Employment status updated!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error updating employment status!'
                            })
                        }
                    })
                }
            })
        });

        function allowNoAttendance(id) {
            Swal.fire({
                title: "Allow No Attendance",
                text : 'Allow this employee to skip logging in biometric attendance?',
                showCancelButton: true,
                confirmButtonText: "Yes",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('employees.allow-no-attendance') }}",
                        type : "GET",
                        data : {
                            id : id,
                            Status : 'Yes'
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                error : 'Allowed for no time-in no time-out.'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                error : 'Error allowing no time-in no time-out!'
                            })
                        }
                    })
                }
            })
        }

        function disAllowNoAttendance(id) {
            Swal.fire({
                title: "Remove No-Attendance",
                text : 'Remove no-attendance policy grant for this employee?',
                showCancelButton: true,
                confirmButtonText: "Yes",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('employees.allow-no-attendance') }}",
                        type : "GET",
                        data : {
                            id : id,
                            Status : null,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                error : 'No attendance policy removed!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                error : 'Error removing no time-in no time-out!'
                            })
                        }
                    })
                }
            })
        }

        function showPayrollSundriesConfig() {
            $('#modal-payroll-sundries').modal('show')

            var sundries = "{{ $payrollSundries }}"
            if (!isNull(sundries)) {
                $('#sundries-longevity').val("{{ $payrollSundries != null ? $payrollSundries->Longevity : '' }}")
                $('#sundries-rice-allowance').val("{{ $payrollSundries != null ? $payrollSundries->RiceAllowance : '' }}")
                $('#sundries-insurance').val("{{ $payrollSundries != null ? $payrollSundries->Insurances : '' }}")
                $('#sundries-pag-ibig-contribution').val("{{ $payrollSundries != null ? $payrollSundries->PagIbigContribution : '' }}")
                $('#sundries-sss-contribution').val("{{ $payrollSundries != null ? $payrollSundries->SSSContribution : '' }}")
                $('#sundries-philhealth').val("{{ $payrollSundries != null ? $payrollSundries->PhilHealth : '' }}")
                $('#sundries-notes').val("{{ $payrollSundries != null ? $payrollSundries->Notes : '' }}")
            }
        }

        function retire() {
            Swal.fire({
                title: "Resign/Retire Confirmation",
                text : `By continuing, you will be ending this employees' company history, payroll data, etc.`,
                showCancelButton: true,
                confirmButtonText: "Proceed",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modal-retire').modal('show')
                }
            });
        }
    </script>
@endpush
