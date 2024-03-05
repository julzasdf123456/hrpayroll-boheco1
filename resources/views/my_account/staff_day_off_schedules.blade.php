@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;

@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <a href="{{ route('users.staff-management') }}" class="btn btn-link-muted" style="margin-right: 30px; display: inline;"><i class="fas fa-arrow-left"></i></a>
            <p class="text-md" style="display: inline-block;">Staff & Subordinates Mangement</p>

            <br>
            <div class="divider"></div>
            <br>
            {{-- leave credit balances --}}
            <div class="section">
                <div class="row">
                    <div class="col-10">
                        <p class="no-pads text-md">Manage {{ $employee->FirstName . ' ' . $employee->LastName  }}'s Day-offs</p>
                        <span class="text-muted">Either set a weekly day-off schedule for this employee or manually add a day-off.</span>
                    </div>
                    <div class="col-2 center-contents">
                        <img style="width: 90% !important;" class="img-fluid" src="{{ asset('imgs/day-offs.png') }}" alt="User profile picture">
                    </div>
                </div>

                <div class="card shadow-none mt-2">
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="text-md" style="display: inline">Day-off Summary</p>
                                <div id="dayoff-loader" class="spinner-border text-success float-right gone" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <br>
                                <span class="text-muted">In the calendar, you can click the day-off to remove.</span>

                                <div class="row mt-3">
                                    <div class="col-lg-5 col-md-12 pr-5 pr-3 pt-3">
                                        <span class="text-muted">Day-off Days</span>
                                        <select class="form-control" id="day-off-sched" onchange="updateDayOff()">
                                            <option value="">-</option>
                                            @foreach ($dayOffs as $item)
                                                <option value="{{ $item->Days }}" {{ $employee->DayOffDates == $item->Days ? 'selected' : '' }}>{{ $item->Days }}</option>
                                            @endforeach
                                        </select>

                                        <div class="divider mt-5 mb-4"></div>

                                        <span class="text-muted">Manually Add a Day-off</span>
                                        <input type="text" id="dayoff" class="form-control mb-2">
                                        @push('page_scripts')
                                            <script type="text/javascript">
                                                $('#dayoff').datetimepicker({
                                                    format: 'YYYY-MM-DD',
                                                    useCurrent: true,
                                                    sideBySide: true,
                                                    icons : {
                                                        previous : 'fas fa-caret-left',
                                                        next : 'fas fa-caret-right',
                                                    }
                                                })
                                            </script>
                                        @endpush
                                        <button onclick="insertDayOff()" class="btn btn-primary float-right">Insert Day-off <i class="fas fa-check ico-tab-left-mini"></i></button>
                                    </div>

                                    <div class="col-lg-7 col-md-12">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('page_scripts')
    <script>
        var dayOffs = [];

        $(document).ready(function() {
            // QUERY dayOffs
            getDayOffsToCalendar()

        })

        function getDayOffsToCalendar() {
            dayOffs = []
            $('#dayoff-loader').removeClass('gone')
            $.ajax({
                url : '{{ route("employeeDayOffs.get-by-employee") }}',
                type : 'GET',
                data : {
                    EmployeeId : "{{ $employee->id }}"
                },
                success : function(res) {
                    moment.tz.setDefault("Asia/Taipei");
                    
                    var hrFormat = "YYYY-MM-DD HH:mm:ss"                    
           
                    $.each(res, function(index, element) {
                        var obj = {}
                        var timestamp = moment(res[index]['DayOff'], 'YYYY-MM-DD')
                        
                        obj['start'] = moment(timestamp).format('YYYY-MM-DD');
                        obj['title'] = 'OFF'
                        obj['backgroundColor'] = '{{ env("DANGER") }}';
                        obj['borderColor'] = '{{ env("DANGER") }}';
                        
                        // urlShow = urlShow.replace("rsId", res[index]['id'])
                        // obj['url'] = urlShow

                        obj['allDay'] = true;
                        dayOffs.push(obj)
                    })

                            /* initialize the calendar
                    -----------------------------------------------------------------*/
                    //Date for the calendar events (dummy data)
                    var date = new Date()
                    var d    = date.getDate(),
                        m    = date.getMonth(),
                        y    = date.getFullYear()

                    var Calendar = FullCalendar.Calendar;

                    var calendarEl = document.getElementById('calendar');
                
                    var calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                            left  : 'prev today',
                            center: 'title',
                            right : 'next'
                        },
                        themeSystem: 'bootstrap',
                        events : dayOffs,
                        eventOrderStrict : true,
                        editable  : true,
                        eventClick : function(info) {
                            Swal.fire({
                                title: "Remove this Day-off?",
                                text : 'You can always re-add this anytime.',
                                showCancelButton: true,
                                confirmButtonText: "Remove",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    var day = moment(info.event.start).format("YYYY-MM-DD")
                                    removeDayOff(day)
                                }
                            })
                        }
                    });

                    calendar.render();
                    $('#dayoff-loader').addClass('gone')
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting day-offs'
                    })
                    $('#dayoff-loader').addClass('gone')
                }
            })
        }

        function insertDayOff() {
            var dayOff = $('#dayoff').val()

            if (isNull(dayOff)) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Add day-off date first!'
                })
            } else {
                $('#dayoff-loader').removeClass('gone')
                $.ajax({
                    url : "{{ route('employeeDayOffs.store') }}",
                    type : "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        DayOff : moment(dayOff).format("YYYY-MM-DD"),
                        EmployeeId : "{{ $employee->id }}",
                        Notes : "Added manually"
                    },
                    success : function(res) {
                        getDayOffsToCalendar()
                        Toast.fire({
                            icon : 'success',
                            text : 'Day-off added manually'
                        })
                        $('#dayoff-loader').addClass('gone')
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error adding day-offs'
                        })
                        $('#dayoff-loader').addClass('gone')
                    }
                })
            }
        }

        function removeDayOff(day) {
            $('#dayoff-loader').removeClass('gone')
            $.ajax({
                url : "{{ route('employeeDayOffs.remove') }}",
                type : "GET",
                data : {
                    Day : day,
                    EmployeeId : "{{ $employee->id }}",
                },
                success : function(res) {
                    getDayOffsToCalendar()
                        Toast.fire({
                            icon : 'success',
                            text : 'Day-off removed!'
                        })
                    $('#dayoff-loader').addClass('gone')
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error removing day-off'
                    })
                    $('#dayoff-loader').addClass('gone')
                }
            })
        }

        function updateDayOff() {
            var dayoff = $('#day-off-sched').val()

            if (!jQuery.isEmptyObject(dayoff)) {
                $.ajax({
                    url : "{{ route('employeePayrollSchedules.update-dayoff') }}",
                    type : "GET",
                    data : {
                        id :  "{{ $employee->id }}",
                        DayOff : dayoff,
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Dayoff updated to ' + dayoff + '!'
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
    </script>
@endpush