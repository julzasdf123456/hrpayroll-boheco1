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
                    </thead>
                    <tbody>
                        @foreach ($employees as $item)
                            <tr>
                                <td><strong><a href="{{ route('employees.show', [$item->id]) }}">{{ Employees::getMergeName($item) }}</a></strong></td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
    </script>
@endpush

