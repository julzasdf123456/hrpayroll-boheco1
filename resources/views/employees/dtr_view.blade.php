@php
    use Carbon\Carbon;

@endphp

<div id="calendar"></div>

@push('page_scripts')
    <script>
        var scheds = [];

        $(document).ready(function() {
            // QUERY SCHEDS
            $.ajax({
                url: '{{ route('employees.get-attendance-data-ajax') }}',
                type: 'GET',
                data: {
                    EmployeeId: "{{ $employees->id }}"
                },
                success: function(res) {
                    moment.tz.setDefault("Asia/Taipei");

                    var hrFormat = "YYYY-MM-DD HH:mm:ss"

                    /**
                     * INSERT BIOMETRICS DATA FROM BIOMETRIC DEVICES
                     **/
                    var biometrics = res['Biometrics']
                    $.each(biometrics, function(index, element) {
                        var obj = {}
                        var timestamp = moment(biometrics[index]['Timestamp'],
                            'YYYY-MM-DD hh:mm:ss')
                        var timeOnly = moment(biometrics[index]['Timestamp'],
                            'YYYY-MM-DD HH:mm:ss')
                        /**
                         * ANALYZE TIME IN AND OUT
                         */
                        var morningStart = moment(timestamp).format('YYYY-MM-DD') +
                            " {{ $workSchedules != null ? $workSchedules->StartTime? Carbon::parse($workSchedules->StartTime)->format('h:i:s'): null : '08:00:00' }}" // 8:00
                        var morningIn = moment(morningStart, 'YYYY-MM-DD HH:mm:ss').add(6,
                            'minutes') // 8:05 AM
                        var morningLate = moment(morningStart, 'YYYY-MM-DD HH:mm:ss').add(16,
                            'minutes') // 8:15 AM
                        var morningAbsent = moment(morningStart, 'YYYY-MM-DD HH:mm:ss').add(2,
                            'hours') // 10 AM

                        var morningEnd = moment(timestamp).format('YYYY-MM-DD') +
                            " {{ $workSchedules != null ? $workSchedules->BreakEnd? Carbon::parse($workSchedules->BreakStart)->format('h:i:s'): null : '12:00:00' }}"
                        morningEnd = moment(morningEnd, 'YYYY-MM-DD HH:mm:ss') // 12:00 NN
                        var morningOut = moment(morningEnd, 'YYYY-MM-DD HH:mm:ss').add(30,
                            'minutes') // 12:30 AM
                        var afternoonStart = moment(timestamp).format('YYYY-MM-DD') +
                            " {{ $workSchedules != null ? $workSchedules->BreakEnd? Carbon::parse($workSchedules->BreakEnd)->format('h:i:s'): null : '13:00:00' }}"
                        afternoonIn = moment(afternoonStart, 'YYYY-MM-DD HH:mm:ss').add(6,
                            'minutes') // 12:05 PM
                        var afternoonLate = moment(afternoonStart, 'YYYY-MM-DD HH:mm:ss').add(
                            16, 'minutes') // 12:15 PM
                        var afternoonAbsent = moment(afternoonStart, 'YYYY-MM-DD HH:mm:ss').add(
                            2, 'hours') // 3:00 PM
                        var afternoonEnd = moment(timestamp).format('YYYY-MM-DD') +
                            " {{ $workSchedules != null ? $workSchedules->EndTime? Carbon::parse($workSchedules->EndTime)->format('h:i:s'): null : '17:00:00' }}"
                        afternoonEnd = moment(afternoonEnd, 'YYYY-MM-DD HH:mm:ss') // 5:00 PM

                        console.log(morningStart + " " + morningEnd + " " + afternoonStart + " " + afternoonEnd);


                        var timeLog = moment(timeOnly, hrFormat)
                        if (timeLog.isBefore(moment(morningIn, hrFormat))) {
                            /** PUNCTUAL IN MORNING **/
                            obj['title'] = 'AM IN: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } else if (timeLog.isBetween(moment(morningIn, hrFormat), moment(
                                morningLate, hrFormat))) {
                            /** LATE IN MORNING **/
                            obj['title'] = 'AM IN: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#97bf08';
                            obj['borderColor'] = '#97bf08';
                        } else if (timeLog.isBetween(moment(morningLate, hrFormat), moment(
                                morningAbsent, hrFormat))) {
                            /** ABSENT IN MORNING **/
                            obj['title'] = 'AM IN: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#bf3f08';
                            obj['borderColor'] = '#bf3f08';
                        } else if (timeLog.isBetween(moment(morningAbsent, hrFormat), moment(
                                morningEnd, hrFormat))) {
                            /** UNDERTIME OUT MORNING **/
                            obj['title'] = 'AM OUT: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#b008bf';
                            obj['borderColor'] = '#b008bf';
                        } else if (timeLog.isBetween(moment(morningEnd, hrFormat), moment(
                                morningOut, hrFormat))) {
                            /** OUT MORNING **/
                            obj['title'] = 'AM OUT: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } else if (timeLog.isBetween(moment(morningEnd, hrFormat), moment(
                                morningOut, hrFormat))) {
                            /** PUNCTUAL IN AFTERNOON **/
                            obj['title'] = 'PM IN: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } else if (timeLog.isBetween(moment(morningOut, hrFormat), moment(
                                afternoonIn, hrFormat))) {
                            /** PUNCTUAL IN AFTERNOON **/
                            obj['title'] = 'PM IN: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        } else if (timeLog.isBetween(moment(afternoonIn, hrFormat), moment(
                                afternoonLate, hrFormat))) {
                            /** LATE IN AFTERNOON **/
                            obj['title'] = 'PM IN: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#97bf08';
                            obj['borderColor'] = '#97bf08';
                        } else if (timeLog.isBetween(moment(afternoonLate, hrFormat), moment(
                                afternoonAbsent, hrFormat))) {
                            /** ABSENT IN AFTERNOON **/
                            obj['title'] = 'PM IN: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#bf3f08';
                            obj['borderColor'] = '#bf3f08';
                        } else if (timeLog.isBetween(moment(afternoonLate, hrFormat), moment(
                                afternoonEnd, hrFormat))) {
                            /** UNDERTIME OUT AFTERNOON **/
                            obj['title'] = 'PM OUT: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#b008bf';
                            obj['borderColor'] = '#b008bf';
                        } else if (timeLog.isAfter(moment(afternoonEnd, hrFormat))) {
                            /** OUT MORNING **/
                            obj['title'] = 'PM OUT: ' + moment(timeLog).format('HH:mm') +
                                ' (' + moment(timeLog).format('hh:mm A') + ')'
                            obj['backgroundColor'] = '#28a745';
                            obj['borderColor'] = '#28a745';
                        }

                        obj['start'] = moment(timestamp).format('YYYY-MM-DD HH:mm:ss');

                        // urlShow = urlShow.replace("rsId", res[index]['id'])
                        // obj['url'] = urlShow

                        obj['allDay'] = true;

                        // insertInOrder(scheds, obj);
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
                        obj['start'] = moment(tripTickets[index]['DateOfTravel']).format(
                            'YYYY-MM-DD');

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
                        obj['start'] = moment(offsets[index]['DateOfOffset']).format(
                            'YYYY-MM-DD');

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
                    var d = date.getDate(),
                        m = date.getMonth(),
                        y = date.getFullYear()

                    var Calendar = FullCalendar.Calendar;

                    var calendarEl = document.getElementById('calendar');

                    var calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        themeSystem: 'bootstrap',
                        events: scheds,
                        eventOrderStrict: true,
                        //     {
                        //         title          : 'Click for Google',
                        //         start          : new Date(y, m, 28),
                        //         end            : new Date(y, m, 29),
                        //         url            : 'https://www.google.com/',
                        //         backgroundColor: '#3c8dbc', //Primary (light-blue)
                        //         borderColor    : '#3c8dbc' //Primary (light-blue)
                        //     }
                        editable: true,
                    });

                    calendar.render();
                },
                error: function(err) {
                    alert('An error occurred while trying to query the schedules')
                }
            })

        })
    </script>
@endpush
