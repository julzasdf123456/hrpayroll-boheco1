@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Edit Work Schedules</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($payrollSchedules, [
                'route' => ['payrollSchedules.update', $payrollSchedules->id],
                'method' => 'patch',
            ]) !!}

            <div class="card-body">
                <div class="row">
                    {{-- @include('payroll_schedules.fields') --}}

                    <!-- Name Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('Name', 'Schedule Name:') !!}
                        {!! Form::text('Name', null, ['class' => 'form-control', 'maxlength' => 300]) !!}
                    </div>

                    <!-- Starttime Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('StartTime', 'Start Time:') !!}
                        {!! Form::text('StartTime',  $payrollSchedules->StartTime? \Carbon\Carbon::parse($payrollSchedules->StartTime)->format('h:i:s') : null, ['class' => 'form-control']) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#StartTime').datetimepicker({
                                format: 'hh:mm A', // 12-hour time format with AM/PM
                                useCurrent: false, // Avoid using the current time as default
                                sideBySide: true, // Keep the time selection side by side
                                showClear: true, // Optionally, add a clear button
                                showClose: true // Optionally, add a close button
                            })
                        </script>
                    @endpush

                    <!-- Breakstart Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('BreakStart', 'Break Start:') !!}
                        {!! Form::text('BreakStart', $payrollSchedules->BreakStart? \Carbon\Carbon::parse($payrollSchedules->BreakStart)->format('h:i:s') : null, ['class' => 'form-control']) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#BreakStart').datetimepicker({
                                format: 'hh:mm A', // 12-hour time format with AM/PM
                                useCurrent: false, // Avoid using the current time as default
                                sideBySide: true, // Keep the time selection side by side
                                showClear: true, // Optionally, add a clear button
                                showClose: true // Optionally, add a close button
                            })
                        </script>
                    @endpush

                    <!-- Breakend Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('BreakEnd', 'Break End:') !!}
                        {!! Form::text('BreakEnd', $payrollSchedules->BreakEnd? \Carbon\Carbon::parse($payrollSchedules->BreakEnd)->format('h:i:s'): null, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#BreakEnd').datetimepicker({
                                format: 'hh:mm A', // 12-hour time format with AM/PM
                                useCurrent: false, // Avoid using the current time as default
                                sideBySide: true, // Keep the time selection side by side
                                showClear: true, // Optionally, add a clear button
                                showClose: true, // Optionally, add a close button    // Add close button
                            })
                        </script>
                    @endpush

                    <!-- Endtime Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('EndTime', 'End Time:') !!}
                        {!! Form::text('EndTime', $payrollSchedules->EndTime? \Carbon\Carbon::parse($payrollSchedules->EndTime)->format('h:i:s'): null, ['class' => 'form-control']) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#EndTime').datetimepicker({
                                format: 'hh:mm A', // 12-hour time format with AM/PM
                                useCurrent: false, // Avoid using the current time as default
                                sideBySide: true, // Keep the time selection side by side
                                showClear: true, // Optionally, add a clear button
                                showClose: true, // Optionally, add a close button    // Add close button
                            })
                        </script>
                    @endpush

                    <!-- Notes Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('Notes', 'Notes:') !!}
                        {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1500]) !!}
                    </div>

                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('payrollSchedules.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
