@php
    use Carbon\Carbon;

    function exportToExcel() {
        return null;
    }
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        {{ $department }} Department Attendance
                    </h1>
                    <p>{{ count($employees) }} Employees marked from {{ Carbon::parse($date1)->format('M d, Y') }} to
                        {{ Carbon::parse($date2)->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card" style="max-width:82vw;">
            <div class="col-lg-9 col-md-8 p-3 w-full">
                <div class="row">
                    <div class="col-lg-1">
                        <span><strong>Legend:</strong></span>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #28a745; padding-left: 10px;">Present</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #ffc107; padding-left: 10px;">Late</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #dc3545; padding-left: 10px;">Absent</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #e30fbc; padding-left: 10px;">Overtime</p>
                    </div>
                    {{-- <div class="col-lg-1">
                        <p style="border-left: 10px solid #0f83e3; padding-left: 10px;">Leave</p>
                    </div> --}}
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #0fe3c9; padding-left: 10px;">Undertime</p>
                    </div>
                </div>
                <div style="overflow-x:auto;overflow-y:auto;height:480px;width: 80vw; max-width:1500px;margin-bottom:30px;">
                    <div style="width: auto;">
                        <table class="table table-hover table-md" id="attendance-table">
                            <thead>
                                <th>Employee No.</th>
                                <th>Employee Name</th>
                                {{-- <th>Position</th> --}}
                                @foreach ($dates as $date)
                                    <th>{{ Carbon::parse($date)->format('M d') }}</th>
                                @endforeach
                                <th>Normal Days</th>
                                <th>Actual Days</th>
                                <th>Absent Days</th>
                                <th>Overtime Hours</th>
                                <th>Undertime Hours</th>
                            </thead>
                            <tbody>
                                <div style="background-color:#28a745;">
                                    @foreach ($employees as $emp)
                                        <tr>
                                            <td>{{ $emp->id }}</td>
                                            <td>{{ $emp->lastname . ', ' . $emp->firstname . ' ' . $emp->middlename }}</td>
                                            {{-- <td>{{ $emp->position }}</td> --}}
                                            @foreach ($dates as $date)
                                                <th>
                                                    <div style="display:flex; justify: center;">
                                                        <div class="am-in_{{ $emp->id }}_{{ $date }}"
                                                            style="padding: 10px 3px; margin: 1px"></div>
                                                        <div class="am-out_{{ $emp->id }}_{{ $date }}"
                                                            style="padding: 10px 3px; margin: 1px"></div>
                                                        <div class="pm-in_{{ $emp->id }}_{{ $date }}"
                                                            style="padding: 10px 3px; margin: 1px"></div>
                                                        <div class="pm-out_{{ $emp->id }}_{{ $date }}"
                                                            style="padding: 10px 3px; margin: 1px"></div>
                                                        {{-- @foreach (['AM IN', 'AM OUT', 'PM IN', 'PM OUT'] as $type)
                                                            <div style="background-color: {{ getAttendanceColor('Present') }}; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, $type, 'Present') }} - Present when AM IN">
                                                            </div>
                                                        @endforeach --}}

                                                        {{-- @if (attendanceMatched($date, $attendanceData, $emp->id, 'AM IN', 'Present'))
                                                            <div style="background-color: #28a745; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'AM IN', 'Present') }} - Present when AM IN">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'AM IN', 'Late'))
                                                            <div style="background-color: #ffc107; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'AM IN', 'Late') }} - Late when AM IN">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'AM IN', 'Absent'))
                                                            <div style="background-color: #dc3545; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'AM IN', 'Absent') }} - Absent when AM IN">
                                                            </div>
                                                        @else
                                                            <div style="padding: 10px 3px; margin: 1px"></div>
                                                        @endif


                                                        @if (attendanceMatched($date, $attendanceData, $emp->id, 'AM OUT', 'Present'))
                                                            <div style="background-color: #28a745; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'AM OUT', 'Present') }} - Present when AM OUT">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'AM OUT', 'Late'))
                                                            <div style="background-color: #ffc107; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'AM OUT', 'Late') }} - Late when AM OUT">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'AM OUT', 'Absent'))
                                                            <div style="background-color: #dc3545; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'AM OUT', 'Absent') }} - Absent when AM OUT">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'AM OUT', 'Undertime'))
                                                            <div style="background-color: #0fe3c9; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'AM OUT', 'Undertime') }} - Undertime when AM OUT">
                                                            </div>
                                                        @else
                                                            <div style="padding: 10px 3px; margin: 1px"></div>
                                                        @endif


                                                        @if (attendanceMatched($date, $attendanceData, $emp->id, 'PM IN', 'Present'))
                                                            <div style="background-color: #28a745; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'PM IN', 'Present') }} - Present when PM IN">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'PM IN', 'Late'))
                                                            <div style="background-color: #ffc107; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'PM IN', 'Late') }} - Late when PM IN">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'PM IN', 'Absent'))
                                                            <div style="background-color: #dc3545; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'PM IN', 'Absent') }} - Absent when PM IN">
                                                            </div>
                                                        @else
                                                            <div style="padding: 10px 3px; margin: 1px"></div>
                                                        @endif


                                                        @if (attendanceMatched($date, $attendanceData, $emp->id, 'PM OUT', 'Present'))
                                                            <div style="background-color: #28a745; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'PM OUT', 'Present') }} - Present when PM OUT">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'PM OUT', 'Late'))
                                                            <div style="background-color: #ffc107; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'PM OUT', 'Late') }} - Late when PM OUT">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'PM OUT', 'Absent'))
                                                            <div style="background-color: #dc3545; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'AM OUT', 'Absent') }} - Absent when PM OUT">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'PM OUT', 'Undertime'))
                                                            <div style="background-color: #0fe3c9; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'PM OUT', 'Undertime') }} - Undertime when PM OUT">
                                                            </div>
                                                        @elseif(attendanceMatched($date, $attendanceData, $emp->id, 'PM OUT', 'Overtime'))
                                                            <div style="background-color: #0fe3c9; padding: 10px 3px; margin: 1px"
                                                                title="{{ convertToDateTime($date, $attendanceData, $emp->id, 'PM OUT', 'Overtime') }} - Overtime when PM OUT">
                                                            </div>
                                                        @else
                                                            <div style="padding: 10px 3px; margin: 1px"></div>
                                                        @endif --}}
                                                    </div>
                                                </th>
                                            @endforeach
                                            <td>
                                                <div class="normal-days_{{ $emp->id }}">{{ count($dates) }}</div>
                                            </td>
                                            <td>
                                                <div class="actual-days_{{ $emp->id }}">0</div>
                                            </td>
                                            <td>
                                                <div class="absent-days_{{ $emp->id }}">0</div>
                                            </td>
                                            <td>
                                                <div class="overtime-hours_{{ $emp->id }}">0</div>
                                            </td>
                                            <td>
                                                <div class="undertime-hours_{{ $emp->id }}">0</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @push('page_scripts')
                                        <script>
                                            $.ajax({
                                                url: "/hr_reports/attendance/reports/employee?department={{ $department }}&date1={{ $date1 }}&date2={{ $date2 }}",
                                                method: 'GET',
                                                success: function(response) {
                                                    res = response.data;
                                                    res.forEach(r => {
                                                        if (r.id == '376-201910') {
                                                            console.log(r);
                                                        }
                                                        switch (r.type) {
                                                            case 'AM IN':
                                                                $(".am-in_" + r.id + "_" + timestampToDate(r.timestamp))
                                                                    .css('background-color', getAttendanceColor(r.status))
                                                                    .attr('title',timestampToDate(r.timestamp) + " " + timestampToTime(r.timestamp) + " - AM IN " + r.status)
                                                                break;
                                                            case 'AM OUT':
                                                                $(".am-out_" + r.id + "_" + timestampToDate(r.timestamp))
                                                                    .css('background-color', getAttendanceColor(r.status))
                                                                    .attr('title',timestampToDate(r.timestamp) + " " + timestampToTime(r.timestamp) + " - AM OUT " + r.status)
                                                                break;
                                                            case 'PM IN':
                                                                $(".pm-in_" + r.id + "_" + timestampToDate(r.timestamp))
                                                                    .css('background-color', getAttendanceColor(r.status))
                                                                    .attr('title',timestampToDate(r.timestamp) + " " + timestampToTime(r.timestamp) + " - PM IN " + r.status)
                                                                break;
                                                            case 'PM OUT':
                                                                $(".pm-out_" + r.id + "_" + timestampToDate(r.timestamp))
                                                                    .css('background-color', getAttendanceColor(r.status))
                                                                    .attr('title',timestampToDate(r.timestamp) + " " + timestampToTime(r.timestamp) + " - PM OUT " + r.status)
                                                                break;
                                                        }
                                                    });
                                                },
                                                error: function(res) {
                                                    console.log(res)
                                                    redirect(-1);
                                                }
                                            })

                                            function timestampToDate(timestamp) {
                                                return new Date(timestamp).toISOString().split('T')[0];
                                            }

                                            function getAttendanceColor(status) {
                                                switch (status) {
                                                    case 'Present':
                                                        return '#28a745';
                                                    case 'Late':
                                                        return '#ffc107';
                                                    case 'Absent':
                                                        return '#dc3545';
                                                    case 'Overtime':
                                                        return '#e30fbc';
                                                    case 'Leave':
                                                        return '#0f83e3';
                                                    case 'Undertime':
                                                        return '#0fe3c9';
                                                    default:
                                                        return '#f0f0f0'; // Default color (no attendance status)
                                                }
                                            }

                                            function timestampToFulldate(timestamp) {
                                                return new Date(timestamp).toLocaleString('en-US', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: 'numeric'
                                                })
                                            }

                                            function timestampToTime(timestamp) {
                                                return new Date(timestamp).toLocaleString('en-US', {
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                    second: '2-digit',
                                                    hour12: true,
                                                })
                                            }
                                        </script>
                                    @endpush
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
                <button class="btn btn-primary">Export as Excelsheet</button>
            </div>
        </div>

    </div>
    </div>
@endsection
