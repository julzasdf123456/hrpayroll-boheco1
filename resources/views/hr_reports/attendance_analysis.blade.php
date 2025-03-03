@php
    use Carbon\Carbon;

    function attendanceMatched($data, $id, $type, $status)
    {
        return collect($data)->where('id', '=', $id)->where('type', '=', $type)->where('status', '=', $status)->first();
    }

    function convertToDateTime($data, $id, $type, $status)
    {
        return Carbon::parse(collect(attendanceMatched($data, $id, $type, $status))['timestamp'])->format(
            'D, M d Y, g:i A',
        );
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
            <div class="col-lg-9 col-md-8 p-3">
                <div class="row">
                    <div class="col-lg-1">
                        <span><strong>Legend:</strong></span>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #28a745; padding-left: 20px;">Present</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #ffc107; padding-left: 20px;">Late</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #dc3545; padding-left: 20px;">Absent</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #e30fbc; padding-left: 20px;">Overtime</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #0f83e3; padding-left: 20px;">Leave</p>
                    </div>
                    <div class="col-lg-2">
                        <p style="border-left: 25px solid #0fe3c9; padding-left: 20px;">Undertime</p>
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
                                                    @if (!stripos($emp->position, 'manager'))
                                                        <div style="display:flex; justify: center;">
                                                            @if (attendanceMatched($attendanceData, $emp->id, 'AM IN', 'Present'))
                                                                <div style="background-color: #28a745; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'AM IN', 'Present') }} - Present">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'AM IN', 'Late'))
                                                                <div style="background-color: #ffc107; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'AM IN', 'Late') }} - Late">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'AM IN', 'Absent'))
                                                                <div style="background-color: #dc3545; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'AM IN', 'Absent') }} - Absent">
                                                                </div>
                                                            @endif

                                                            @if (attendanceMatched($attendanceData, $emp->id, 'AM OUT', 'Present'))
                                                                <div style="background-color: #28a745; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'AM OUT', 'Present') }} - Present">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'AM OUT', 'Late'))
                                                                <div style="background-color: #ffc107; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'AM OUT', 'Late') }} - Late">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'AM OUT', 'Absent'))
                                                                <div style="background-color: #dc3545; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'AM OUT', 'Absent') }} - Absent">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'AM OUT', 'Undertime'))
                                                                <div style="background-color: #0fe3c9; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'AM OUT', 'Undertime') }} - Undertime">
                                                                </div>
                                                            @endif


                                                            @if (attendanceMatched($attendanceData, $emp->id, 'PM IN', 'Present'))
                                                                <div style="background-color: #28a745; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'PM IN', 'Present') }} - Present">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'PM IN', 'Late'))
                                                                <div style="background-color: #ffc107; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'PM IN', 'Late') }} - Late">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'PM IN', 'Absent'))
                                                                <div style="background-color: #dc3545; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'PM IN', 'Absent') }} - Absent">
                                                                </div>
                                                            @endif


                                                            @if (attendanceMatched($attendanceData, $emp->id, 'PM OUT', 'Present'))
                                                                <div style="background-color: #28a745; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'PM OUT', 'Present') }} - Present">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'PM OUT', 'Late'))
                                                                <div style="background-color: #ffc107; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'PM OUT', 'Late') }} - Late">
                                                                </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'PM OUT', 'Absent'))
                                                                <div style="background-color: #dc3545; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'AM OUT', 'Absent') }} - Absent">
                                                                </div>
                                                                @elseif(attendanceMatched($attendanceData, $emp->id, 'PM OUT', 'Undertime'))
                                                                    <div style="background-color: #0fe3c9; padding: 10px 3px; margin: 1px"
                                                                        title="{{ convertToDateTime($attendanceData, $emp->id, 'PM OUT', 'Undertime') }} - Undertime">
                                                                    </div>
                                                            @elseif(attendanceMatched($attendanceData, $emp->id, 'PM OUT', 'Overtime'))
                                                                <div style="background-color: #0fe3c9; padding: 10px 3px; margin: 1px"
                                                                    title="{{ convertToDateTime($attendanceData, $emp->id, 'PM OUT', 'Overtime') }} - Overtime">
                                                                </div>
                                                            @endif

                                </div>
                            @else
                                <div style="display:flex; justify: center;">
                                    <div class="{{ $emp->id }}_am-in_{{ $date }}"
                                        style="background-color: #28a745; padding: 10px 3px; margin: 1px" title="Present">
                                    </div>
                                    <div class="{{ $emp->id }}_am-in_{{ $date }}"
                                        style="background-color: #28a745; padding: 10px 3px; margin: 1px" title="Present">
                                    </div>
                                    <div class="{{ $emp->id }}_am-in_{{ $date }}"
                                        style="background-color: #28a745; padding: 10px 3px; margin: 1px" title="Present">
                                    </div>
                                    <div class="{{ $emp->id }}_am-in_{{ $date }}"
                                        style="background-color: #28a745; padding: 10px 3px; margin: 1px" title="Present">
                                    </div>
                                </div>
                                @endif
                                </th>
                                @endforeach
                                <td>{{ count($dates) }}</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                </tr>
                                @endforeach
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
