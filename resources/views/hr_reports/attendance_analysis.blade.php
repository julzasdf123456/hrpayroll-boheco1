@php

@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        {{ $department }} Departmental Attendance
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
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
                </div>
                <div style="overflow-y:auto;height:350px;width:80vw; max-width:1500px;margin-bottom:20px;">
                    <table class="table table-hover table-sm" id="attendance-table">
                        <thead>
                            <th>Employee No.</th>
                            <th>Employee Name</th>
                            @foreach ($dates as $date)
                                <th>{{ $date }}</th>
                            @endforeach
                            <th>Normal Days</th>
                            <th>Actual Days</th>
                            <th>Absent Days</th>
                            <th>Overtime Hours</th>
                        </thead>
                        <tbody>
                            @foreach ($employees as $emp)
                            <tr>
                                <td>{{ $emp->id }}</td>
                                <td>{{ $emp->lastname . ", " . $emp->firstname . " " . $emp->middlename}}</td>
                                <td>_</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
