@php
    use Carbon\Carbon;

    $colorProf = Auth::user()->ColorProfile;


    $years = [];
    for($i=0; $i<24; $i++) {
        $years[$i] = date('Y', strtotime('today -' . $i . ' years'));
    }
@endphp
@extends('layouts.app')

@section('content')
<meta name="employee-id-current" content="{{ Auth::user()->employee_id }}">
<meta name="user-id-current" content="{{ Auth::id() }}">
<div class="content">
    <div class="row">
        <div class="col-lg-12" style="margin-bottom: 26px;">
            <p class="text-center no-pads text-lg">Attendance Management</p>
            <p class="text-center no-pads text-muted">View and monitor your daily attendance logs.</p>
        </div>
    </div>

    {{-- CONTENT LINEAR --}}
    <div class="col-lg-10 offset-lg-1">
        {{-- DTR --}}
        <div class="section">
            <div class="row">
                <div class="col-10 relative">
                    <div class="botom-left-contents px-3">
                        <p class="no-pads text-md">Your DTR and Duty Loggs</p>
                        <p class="no-pads text-muted">A quick peek of your biometric-based daily time record, leaves, trips, offsets, and more in one integrated calendar. 
                            Your supers can also see these data.</p>
                    </div>
                </div>
                <div class="col-2 center-contents">
                    <img style="width: 80% !important;" class="img-fluid" src="{{ asset('imgs/attendances.png') }}" alt="User profile picture">
                </div>
            </div>

            {{-- payslip summary table --}}
            <div class="card shadow-none mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="text-md">Daily Attendance Calendar</p>
                        </div>
                        <div class="col-lg-4 col-md-12 mt-3">
                            <span class="text-muted">Legend:</span>
                            <table class="table table-borderless table-sm table-hover mt-3">
                                <tbody>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend bg-success"></span>
                                        </td>
                                        <td>Punctually Present</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="background-color: #97bf08;"></span>
                                        </td>
                                        <td>Late</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="background-color: #b008bf;"></span>
                                        </td>
                                        <td>Undertimed</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="background-color: #bf3f08;"></span>
                                        </td>
                                        <td>Absent AM/PM (No Time-in or out)</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="background-color: #e823ba;"></span>
                                        </td>
                                        <td>On a Trip</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="background-color: #3254a8;"></span>
                                        </td>
                                        <td>Official Travel</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="background-color: #0cf2c4;"></span>
                                        </td>
                                        <td>Leave</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="background-color: #c99402;"></span>
                                        </td>
                                        <td>Offset</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="background-color: #1d8fcc;"></span>
                                        </td>
                                        <td>Day-off</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30px;">
                                            <span class="color-legend" style="border: 1px solid #c5c5c5;"></span>
                                        </td>
                                        <td>Absent/AWOL</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-8 col-md-12">
                            <div class="p-3" id="calendar"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    
                </div>
            </div>
        </div>

        {{-- OTHERS --}}
        <div id="app">
            <attendance-index></attendance-index>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        var scheds = []
        $(document).ready(function() {
            getDTR()
        })

        function getDTR() {
            $.ajax({
                url : '{{ route("employees.get-attendance-data-ajax") }}',
                type : 'GET',
                data : {
                    EmployeeId : "{{ Auth::user()->employee_id }}"
                },
                success : function(res) {
                    moment.tz.setDefault("Asia/Taipei");
                    
                    var hrFormat = "YYYY-MM-DD HH:mm:ss"                    

                    /**
                     * INSERT BIOMETRICS DATA FROM BIOMETRIC DEVICES
                     **/
                    var biometrics = res['Biometrics']                    
                    $.each(biometrics, function(index, element) {
                        var obj = {}
                        var timestamp = moment(biometrics[index]['Timestamp'], 'YYYY-MM-DD hh:mm:ss')
                        var timeOnly = moment(biometrics[index]['Timestamp'], 'YYYY-MM-DD HH:mm:ss')
                        /**
                         * ANALYZE TIME IN AND OUT
                         */
                        // var morningStart = moment(timestamp).format('YYYY-MM-DD') + " {{ $workSchedules != null ? Carbon::parse($workSchedules->StartTime)->format('H:i:s') : '08:00:00' }}" // 8:00
                        var morningStart = moment(timestamp).format('YYYY-MM-DD') + " {{ $workSchedules != null ? Carbon::parse($workSchedules->StartTime)->format('H:i:s') : null }}" // 8:00
                        var morningIn = moment(morningStart, 'YYYY-MM-DD HH:mm:ss').add(30, 'minutes') // 8:05 AM
                        var morningLate = moment(morningStart, 'YYYY-MM-DD HH:mm:ss').add(16, 'minutes') // 8:15 AM
                        var morningAbsent = moment(morningStart, 'YYYY-MM-DD HH:mm:ss').add(2, 'hours') // 10 AM
                        
                        // var morningEnd =  moment(timestamp).format('YYYY-MM-DD') + " {{ $workSchedules != null ? Carbon::parse($workSchedules->BreakStart)->format('H:i:s') : '12:00:00' }}"
                        var morningEnd =  moment(timestamp).format('YYYY-MM-DD') + " {{ $workSchedules != null ? Carbon::parse($workSchedules->BreakStart)->format('H:i:s') : null }}"
                        morningEnd = moment(morningEnd, 'YYYY-MM-DD HH:mm:ss') // 12:00 NN
                        var morningOut = moment(morningEnd, 'YYYY-MM-DD HH:mm:ss').add(30,'minutes') // 12:30 AM
                        // var afternoonStart =  moment(timestamp).format('YYYY-MM-DD') + " {{ $workSchedules != null ? Carbon::parse($workSchedules->BreakEnd)->format('H:i:s') : '13:00:00' }}"
                        var afternoonStart =  moment(timestamp).format('YYYY-MM-DD') + " {{ $workSchedules != null ? Carbon::parse($workSchedules->BreakEnd)->format('H:i:s') : null }}"
                        afternoonIn = moment(afternoonStart, 'YYYY-MM-DD HH:mm:ss').add(6, 'minutes') // 12:05 PM
                        var afternoonLate = moment(afternoonStart, 'YYYY-MM-DD HH:mm:ss').add(16, 'minutes') // 12:15 PM
                        var afternoonAbsent = moment(afternoonStart, 'YYYY-MM-DD HH:mm:ss').add(2, 'hours') // 3:00 PM
                        // var afternoonEnd =  moment(timestamp).format('YYYY-MM-DD') + " {{ $workSchedules != null ? Carbon::parse($workSchedules->EndTime)->format('H:i:s') : '17:00:00' }}"
                        var afternoonEnd =  moment(timestamp).format('YYYY-MM-DD') + " {{ $workSchedules != null ? Carbon::parse($workSchedules->EndTime)->format('H:i:s') : null }}"
                        afternoonEnd = moment(afternoonEnd, 'YYYY-MM-DD HH:mm:ss') // 5:00 PM

                        var timeLog = moment(timeOnly, hrFormat)
                        if (timeLog.isBefore( moment(morningIn, hrFormat) )) {
                            /** PUNCTUAL IN MORNING **/
                            obj['title'] = 'AM IN: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } else if (timeLog.isBetween( moment(morningIn, hrFormat) , moment(morningLate, hrFormat))) {
                            /** LATE IN MORNING **/
                            obj['title'] = 'AM IN: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#97bf08';
                            obj['borderColor'] = '#97bf08';
                        } else if (timeLog.isBetween( moment(morningLate, hrFormat) , moment(morningAbsent, hrFormat))) {
                            /** ABSENT IN MORNING **/
                            obj['title'] = 'AM IN: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#bf3f08';
                            obj['borderColor'] = '#bf3f08';
                        } else if (timeLog.isBetween( moment(morningAbsent, hrFormat) , moment(morningEnd, hrFormat))) {
                            /** UNDERTIME OUT MORNING **/
                            obj['title'] = 'AM OUT: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#b008bf';
                            obj['borderColor'] = '#b008bf';
                        } else if (timeLog.isBetween( moment(morningEnd, hrFormat) , moment(morningOut, hrFormat))) {
                            /** OUT MORNING **/
                            obj['title'] = 'AM OUT: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } else if (timeLog.isBetween( moment(morningEnd, hrFormat) , moment(morningOut, hrFormat))) {
                            /** PUNCTUAL IN AFTERNOON **/
                            obj['title'] = 'PM IN: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } else if (timeLog.isBetween( moment(morningOut, hrFormat) , moment(afternoonIn, hrFormat))) {
                            /** PUNCTUAL IN AFTERNOON **/
                            obj['title'] = 'PM IN: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } else if (timeLog.isBetween( moment(afternoonIn, hrFormat) , moment(afternoonLate, hrFormat))) {
                            /** LATE IN AFTERNOON **/
                            obj['title'] = 'PM IN: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#97bf08';
                            obj['borderColor'] = '#97bf08';
                        } else if (timeLog.isBetween( moment(afternoonLate, hrFormat) , moment(afternoonAbsent, hrFormat))) {
                            /** ABSENT IN AFTERNOON **/
                            obj['title'] = 'PM IN: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#bf3f08';
                            obj['borderColor'] = '#bf3f08';
                        } else if (timeLog.isBetween( moment(afternoonLate, hrFormat) , moment(afternoonEnd, hrFormat))) {
                            /** UNDERTIME OUT AFTERNOON **/
                            obj['title'] = 'PM OUT: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#b008bf';
                            obj['borderColor'] = '#b008bf';


                            {{ !$workSchedules->BreakStart && !$workSchedules->BreakEnd? 'return true;': '' }}
                        } else if (timeLog.isAfter( moment(afternoonEnd, hrFormat) )) {
                            /** OUT MORNING **/
                            obj['title'] = 'PM OUT: ' + moment(timeLog).format('HH:mm')  + " (" + moment(timeLog).format('hh:mm A') + ")" 
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } 



                        // if (moment(timestamp).format('YYYY-MM-DD') == '2025-02-27') {
                        //     console.log( 
                        //     " {{ $workSchedules->StartTime }} ", 
                        //     " {{ $workSchedules->BreakStart }} ", 
                        //     " {{ Carbon::parse($workSchedules->BreakEnd)->format('H:i:s') }} ", 
                        //     " {{ $workSchedules->EndTime }} ", 
                        // )
                        //     console.log("PM IN - ", timeLog.isBetween( moment(morningOut, hrFormat) , moment(afternoonIn, hrFormat)) + " " + moment(timeLog, hrFormat).format('hh:mm A') + " " + moment(morningOut).format('hh:mm A') + " " + moment(afternoonIn).format('hh:mm A'))
                        // }
                        
                        obj['start'] = moment(timestamp).format('YYYY-MM-DD');
                        
                        // urlShow = urlShow.replace("rsId", res[index]['id'])
                        // obj['url'] = urlShow

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

                    /**
                     * INSERT LEAVE DATA
                     **/
                     var leave = res['Leave']
                    $.each(leave, function(index, element) {
                        var obj = {}

                        if (leave[index]['Duration'] == 'WHOLE') {
                            obj['title'] = 'LEAVE (WHOLE DAY)'
                        } else {
                            obj['title'] = 'LEAVE (' + leave[index]['Duration'] + ')'
                        }
                        obj['backgroundColor'] = '#0cf2c4';
                        obj['borderColor'] = '#0cf2c4';
                        obj['start'] = moment(leave[index]['LeaveDate']).format('YYYY-MM-DD');

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

                    /**
                     * TRIP TICKET DATA
                     **/
                    var tripTickets = res['TripTickets']
                    $.each(tripTickets, function(index, element) {
                        var obj = {}

                        obj['title'] = 'TRIP'
                        obj['backgroundColor'] = '#e823ba';
                        obj['borderColor'] = '#e823ba';
                        obj['start'] = moment(tripTickets[index]['DateOfTravel']).format('YYYY-MM-DD');

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

                    /**
                     * OFFSET DATA
                     **/
                    var offsets = res['Offsets']
                    $.each(offsets, function(index, element) {
                        var obj = {}

                        obj['title'] = 'OFFSET'
                        obj['backgroundColor'] = '#f2780c';
                        obj['borderColor'] = '#f2780c';
                        obj['start'] = moment(offsets[index]['DateOfOffset']).format('YYYY-MM-DD');

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

                    /**
                     * DAY OFFS
                     **/
                    var dayOffs = res['DayOffs']
                    $.each(dayOffs, function(index, element) {
                        var obj = {}

                        obj['title'] = 'DAY OFF'
                        obj['backgroundColor'] = '#1d8fcc';
                        obj['borderColor'] = '#1d8fcc';
                        obj['start'] = moment(dayOffs[index]['DayOff']).format('YYYY-MM-DD');

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

                    /**
                     * TRAVEL ORDERS
                     **/
                     var travels = res['TravelOrders']
                    $.each(travels, function(index, element) {
                        var obj = {}

                        obj['title'] = 'TRAVEL'
                        obj['backgroundColor'] = '#3254a8';
                        obj['borderColor'] = '#3254a8';
                        obj['start'] = moment(travels[index]['Day']).format('YYYY-MM-DD');

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })
                    // scheds = scheds.filter(function (obj) { 
                    //     return obj.start !== '2024-01-17'
                    // })
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
                        events : scheds,
                        eventOrderStrict : true,
                        //     {
                        //         title          : 'Click for Google',
                        //         start          : new Date(y, m, 28),
                        //         end            : new Date(y, m, 29),
                        //         url            : 'https://www.google.com/',
                        //         backgroundColor: '#3c8dbc', //Primary (light-blue)
                        //         borderColor    : '#3c8dbc' //Primary (light-blue)
                        //     }
                        editable  : true,
                    });

                    calendar.render();
                },
                error : function(err) {
                    alert('An error occurred while trying to query the schedules')
                }
            })
        }
    </script>
@endpush