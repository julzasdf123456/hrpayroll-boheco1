@php
    use Carbon\Carbon;

    function exportToExcel()
    {
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
                    <p>{{ count($employees) }} employees marked as from {{ Carbon::parse($date1)->format('M d, Y') }} to
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
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #fadbd8; padding-left: 10px;">Undertime</p>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-lg-1">
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #fadbd8; padding-left: 10px;">Undertime</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #0fe3c9; padding-left: 10px;">Paid Leave</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #717d7e; padding-left: 10px;">Unpaid Leave</p>
                    </div> 
                </div> --}}
                <div style="overflow-x:auto;overflow-y:auto;height:600px;width:85vw;max-width:1500px;margin-bottom:30px;">
                    <div style="width: fit;">
                        <table class="table table-hover table-md" id="attendance-table">
                            <thead>
                                <th>ID No.</th>
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
                                                            style="padding: 13px 6px; margin: 1px; color:white;"></div>
                                                        <div class="am-out_{{ $emp->id }}_{{ $date }}"
                                                            style="padding: 13px 6px; margin: 1px; color:white;"></div>
                                                        <div class="pm-in_{{ $emp->id }}_{{ $date }}"
                                                            style="padding: 13px 6px; margin: 1px; color:white;"></div>
                                                        <div class="pm-out_{{ $emp->id }}_{{ $date }}"
                                                            style="padding: 13px 6px; margin: 1px; color:white;"></div>
                                                    </div>
                                                </th>
                                            @endforeach
                                            <td>
                                                <p class="normal-days_{{ $emp->id }}">{{ count($dates) }}</p>
                                            </td>
                                            <td>
                                                <p class="actual-days_{{ $emp->id }}">0</p>
                                            </td>
                                            <td>
                                                <p class="absent-days_{{ $emp->id }}">0</p>
                                            </td>
                                            <td>
                                                <p class="overtime-hours_{{ $emp->id }}">0</p>
                                            </td>
                                            <td>
                                                <p class="undertime-hours_{{ $emp->id }}">0</p>
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
                                                        // if (r.id == '58-199205') {
                                                        //     console.log(r.timestamp);
                                                        // }
                                                        
                                                        switch (r.type) {
                                                            case 'AM IN':
                                                                $(".am-in_" + r.id + "_" + timestampToDate(r.timestamp))
                                                                    .css('background-color', getAttendanceColor(r.status))
                                                                    .attr('title', timestampToDate(r.timestamp) + " " + timestampToTime(r
                                                                        .timestamp) + " - AM IN " + r.status)
                                                                    if (r.id == '435-202307') {
                                                                        // console.log(r.timestamp);
                                                                        console.log(r);
                                                                        // console.log(r.timestamp + " | " + timestampToTime(r.timestamp)+ " | " + timestampToDate(r.timestamp));
                                                                        console.log(".am-in_" + r.id + "_" + timestampToDate(r.timestamp)+" = "+r.timestamp)
                                                                    }
                                                                    break;
                                                            case 'AM OUT':
                                                                $(".am-out_" + r.id + "_" + timestampToDate(r.timestamp))
                                                                    .css('background-color', getAttendanceColor(r.status))
                                                                    .attr('title', timestampToDate(r.timestamp) + " " + timestampToTime(r
                                                                        .timestamp) + " - AM OUT " + r.status)
                                                                    if (r.id == '435-202307') {
                                                                        // console.log(r.timestamp);
                                                                        console.log(r);
                                                                        // console.log(r.timestamp + " | " + timestampToTime(r.timestamp)+ " | " + timestampToDate(r.timestamp));
                                                                        console.log(".am-out_" + r.id + "_" + timestampToDate(r.timestamp)+" = "+r.timestamp)
                                                                    }
                                                                        break;
                                                            case 'PM IN':
                                                                $(".pm-in_" + r.id + "_" + timestampToDate(r.timestamp))
                                                                    .css('background-color', getAttendanceColor(r.status))
                                                                    .attr('title', timestampToDate(r.timestamp) + " " + timestampToTime(r
                                                                        .timestamp) + " - PM IN " + r.status)
                                                                    if (r.id == '435-202307') {
                                                                        // console.log(r.timestamp);
                                                                        console.log(r);
                                                                        // console.log(r.timestamp + " | " + timestampToTime(r.timestamp)+ " | " + timestampToDate(r.timestamp));
                                                                        console.log(".pm-in_" + r.id + "_" + timestampToDate(r.timestamp)+" = "+r.timestamp)
                                                                    }
                                                                break;
                                                            case 'PM OUT':
                                                                $(".pm-out_" + r.id + "_" + timestampToDate(r.timestamp))
                                                                    .css('background-color', getAttendanceColor(r.status))
                                                                    .attr('title', timestampToDate(r.timestamp) + " " + timestampToTime(r
                                                                        .timestamp) + " - PM OUT " + r.status)
                                                                    if (r.id == '435-202307') {
                                                                        // console.log(r.timestamp);
                                                                        console.log(r);
                                                                        // console.log(r.timestamp + " | " + timestampToTime(r.timestamp)+ " | " + timestampToDate(r.timestamp));
                                                                        console.log(".pm-out_" + r.id + "_" + timestampToDate(r.timestamp)+" = "+r.timestamp)
                                                                    }
                                                                    break;
                                                        }
                                                    });
                                                },
                                                error: function(res) {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        text: 'Error fetching attendance data of all employees. Please contact IT support.'
                                                    })
                                                }
                                            })

                                            function timestampToDate(timestamp) {
                                                // console.log(timestamp + " | " + new Date(timestamp).toISOString().split('T')[0])
                                                // return new Date(timestamp).toISOString().split('T')[0];
                                                return timestamp.split(' ')[0];
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
                                                        return '#fadbd8';
                                                    case 'Leave':
                                                        return '#0fe3c9';
                                                    case 'Unpaid':
                                                        return '#717d7e';
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
