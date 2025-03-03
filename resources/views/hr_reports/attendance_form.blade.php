@php

@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Create Departmental Attendance Report
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">


        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
            <div class="card col">
                @if (session('error'))
                    <script type="text/javascript">
                        alert('{{ session('error') }}');
                    </script>
                @endif


                {!! Form::open(['route' => 'hr_reports.attendance_store', 'method' => 'GET', 'class' => 'col']) !!}
                <div class="form-group row-sm-12">
                    <div class="col mt-5">
                        {!! Form::label('department', 'Select a Department.') !!}
                        <select class="form-control" name="department" id="department" required>
                            <option value="">-- Select --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mt-5 px-3">
                        {!! Form::label('dates', 'Select dates for Reporting.') !!}
                        <div class="row">

                            {!! Form::date('from_date', null, ['class' => 'form-control col px-3', 'required' => 'true']) !!}
                            <div class="mx-3">_</div>
                            {!! Form::date('to_date', null, ['class' => 'form-control col px-3', 'required' => 'true']) !!}
                        </div>
                    </div>
                    <div class="col mt-5 px-3">
                        {!! Form::submit('Generate Report', ['class' => 'btn btn-primary']) !!}

                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Get today's date in 'YYYY-MM-DD' format
                    const today = new Date();
                    const todayString = today.toISOString().split('T')[0]; // Get 'YYYY-MM-DD'

                    const fromDateInput = document.getElementById('from_date');
                    const toDateInput = document.getElementById('to_date');

                    // Set default value to today's date if no value is provided
                    if (!fromDateInput.value) fromDateInput.value = todayString;
                    if (!toDateInput.value) toDateInput.value = todayString;

                    // Form submission validation
                    document.querySelector('form').addEventListener('submit', function(event) {
                        const fromDate = new Date(fromDateInput.value);
                        const toDate = new Date(toDateInput.value);

                        // Validate that the date range is not more than 15 days
                        const dateDifference = (toDate - fromDate) / (1000 * 3600 *
                            24); // Convert difference to days

                        if (dateDifference > 15) {
                            alert('The date range should not exceed 15 days.');
                            event.preventDefault(); // Prevent form submission if validation fails
                        }
                    });
                });
            </script>

        </div>
    </div>
    </div>
@endsection
