@php
    use App\Models\Employees;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Employee Quick Configuration</h4>
                    <span class="text-muted">Fast configuration of employees' shifts, day-offs, etc.</span>
                </div>
                <div class="col-sm-6">
                    {{-- <a class="btn btn-primary float-right"
                        href="{{ route('payrollSchedules.create') }}">
                        Create New Work Schedule
                    </a> --}}

                    <form action="{{ route('employeePayrollSchedules.index') }}" method="GET">
                        <button class="btn btn-primary float-right">Filter</button>
                        <select name="Department" class="form-control form-sm float-right" style="width: 200px; margin-right: 10px;">
                            <option value="">All</option>
                            @foreach ($departments as $item)
                                <option value="{{ $item->Department }}" {{ isset($_GET['Department']) && $item->Department==$_GET['Department'] ? 'selected' : '' }}>{{ $item->Department }}</option>
                            @endforeach
                            <option value="SUB-OFFICE" {{ isset($_GET['Department']) && $item->Department==$_GET['Department'] ? 'selected' : '' }}>SUB-OFFICE</option>
                        </select>
                        {{-- <input class="form-control form-sm float-right" style="width: 400px; margin-right: 10px;" value="" placeholder="Search a Name" name="Name" /> --}}
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card shadow-none">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Shift Schedule</th>
                        <th>Day Offs</th>
                        <th>Office</th>
                        <th>Date Hired</th>
                        <th>Biometrics ID</th>
                        <th>Pitakard No.</th>
                        <th>Contact Nos.</th>
                    </thead>
                    <tbody>
                        @foreach ($employees as $item)
                            <tr>
                                <td><strong><a href="{{ route('employees.show', [$item->id]) }}">{{ Employees::getMergeNameFormal($item) }}</a></strong></td>
                                <td>{{ $item->Department }}</td>
                                <td>{{ $item->Position }}</td>
                                <td>
                                    <select id="sched-{{ $item->id }}" class="form-control form-control-sm" onchange="update('{{ $item->id }}')">
                                        <option value="">-</option>
                                        @foreach ($schedules as $itemx)
                                            <option value="{{ $itemx->id }}" {{ $item->PayrollScheduleId == $itemx->id ? 'selected' : '' }}>{{ $itemx->Name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="off-{{ $item->id }}" class="form-control form-control-sm" onchange="updateDayOff('{{ $item->id }}')">
                                        <option value="">-</option>
                                        @foreach ($dayOffs as $itemy)
                                            <option value="{{ $itemy->Days }}" {{ $item->DayOffDates == $itemy->Days ? 'selected' : '' }}>{{ $itemy->Days }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="office-{{ $item->id }}" class="form-control form-control-sm" onchange="updateOffice('{{ $item->id }}')">
                                        <option value="MAIN OFFICE" {{ $item->OfficeDesignation == 'MAIN OFFICE' ? 'selected' : '' }}>MAIN OFFICE</option>
                                        <option value="SUB-OFFICE" {{ $item->OfficeDesignation == 'SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>
                                    </select>
                                </td>
                                <td>
                                    <input id="date-hired-{{ $item->id }}" type="date" class="form-control form-control-sm" value="{{ $item->DateHired != null ? $item->DateHired : '' }}" onchange="updateDateHired(`{{ $item->id }}`)">
                                </td>
                                <td style="width: 160px;">
                                    <input id="biometric-id-{{ $item->id }}" onchange="updateBiometricId(`{{ $item->id }}`)" type="number" class="form-control form-control-sm" value="{{ $item->BiometricsUserId }}">
                                </td>
                                <td style="width: 220px;">
                                    <input id="pitakard-{{ $item->id }}" onchange="updatePitakard(`{{ $item->id }}`)" type="text" class="form-control form-control-sm" value="{{ $item->PrimaryBankNumber }}">
                                </td>
                                <td style="width: 220px;">
                                    <input id="contact-{{ $item->id }}" onchange="updateContact(`{{ $item->id }}`)" type="text" class="form-control form-control-sm" value="{{ $item->ContactNumbers }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="d-flex justify-content-center p-4">
                    {{ $employees->appends(['search' => request('search')])->links() }}
                </div> --}}
            </div>
        </div>
    </div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
        })

        function update(id) {
            var sched = $('#sched-' + id).val()

            if (!jQuery.isEmptyObject(sched)) {
                $.ajax({
                    url : "{{ route('employeePayrollSchedules.create-schedule') }}",
                    type : "GET",
                    data : {
                        EmployeeId : id,
                        ScheduleId : sched,
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Schedule updated!'
                        })
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error setting schedule!'
                        })
                    }
                })
            }            
        }

        function updateDayOff(id) {
            var dayoff = $('#off-' + id).val()

            if (!jQuery.isEmptyObject(dayoff)) {
                $.ajax({
                    url : "{{ route('employeePayrollSchedules.update-dayoff') }}",
                    type : "GET",
                    data : {
                        id : id,
                        DayOff : dayoff,
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Dayoff updated!'
                        })
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error setting dayoff!'
                        })
                    }
                })
            }            
        }

        function updateOffice(id) {
            var office = $('#office-' + id).val()

            $.ajax({
                url : "{{ route('employees.update-office') }}",
                type : "GET",
                data : {
                    id : id,
                    Office : office,
                },
                success : function (res) {  
                    Toast.fire({
                        icon : 'success',
                        text : 'Office updated!'
                    })
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error updating office!'
                    })
                }
            })
        }

        function updateDateHired(id) {
            var date = $('#date-hired-' + id).val()

            $.ajax({
                url : "{{ route('employees.update-date-hired') }}",
                type : "GET",
                data : {
                    id : id,
                    DateHired : date,
                },
                success : function (res) {  
                    Toast.fire({
                        icon : 'success',
                        text : 'Date hired updated!'
                    })
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error updating date hired!'
                    })
                }
            })
        }

        function updateBiometricId(id) {
            var bioId = $('#biometric-id-' + id).val()

            if (isNull(bioId)) {

            } else {
                $.ajax({
                    url : "{{ route('employees.update-biometrics-id') }}",
                    type : "GET",
                    data : {
                        id : id,
                        BiometricsId : bioId
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Biometrics ID updated!'
                        })
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error updating biometrics ID!'
                        })
                    }
                })
            }            
        }

        function updatePitakard(id) {
            var pitakardNo = $('#pitakard-' + id).val()

            if (isNull(pitakardNo)) {

            } else {
                $.ajax({
                    url : "{{ route('employees.update-pitakard') }}",
                    type : "GET",
                    data : {
                        id : id,
                        PitakardNo : pitakardNo
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Pitakard number updated!'
                        })
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error updating pitakard number!'
                        })
                    }
                })
            }            
        }

        function updateContact(id) {
            var contact = $('#contact-' + id).val()

            $.ajax({
                url : "{{ route('employees.update-contact-numbers') }}",
                type : "GET",
                data : {
                    id : id,
                    ContactNumbers : contact
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Contact number updated!'
                    })
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error updating contact number!'
                    })
                }
            })           
        }
    </script>
@endpush

