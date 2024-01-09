@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>{{ Employees::getMergeName($employee) }}'s Biometric Attendance</h4>
            </div>
            <div class="col-sm-6">
                <a class="btn btn-default float-right"
                   href="{{ route('employees.index') }}">
                    Back
                </a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-3 col-md-4">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Filter</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="month">Month</label>
                    <select class="form-control" name="month" id="month">
                        <option value="01" {{ date('F')=='January' ? 'selected' : '' }}>January</option>
                        <option value="02" {{ date('F')=='February' ? 'selected' : '' }}>February</option>
                        <option value="03" {{ date('F')=='March' ? 'selected' : '' }}>March</option>
                        <option value="04" {{ date('F')=='April' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ date('F')=='May' ? 'selected' : '' }}>May</option>
                        <option value="06" {{ date('F')=='June' ? 'selected' : '' }}>June</option>
                        <option value="07" {{ date('F')=='July' ? 'selected' : '' }}>July</option>
                        <option value="08" {{ date('F')=='August' ? 'selected' : '' }}>August</option>
                        <option value="09" {{ date('F')=='September' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ date('F')=='October' ? 'selected' : '' }}>October</option>
                        <option value="11" {{ date('F')=='November' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ date('F')=='December' ? 'selected' : '' }}>December</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="month">Year</label>
                    <select class="form-control" name="year" id="year">
                        @for ($i = 0; $i < 20; $i++)                            
                            <option value="{{ date('Y', strtotime('today - ' . $i . ' years')) }}" {{ date('Y')==date('Y', strtotime('today - ' . $i . ' years')) ? 'selected' : '' }}>{{ date('Y', strtotime('today - ' . $i . ' years')) }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8">
        <div class="row">
            <div class="col-lg-1">
                <span><strong>Legend:</strong></span>
            </div>
            <div class="col-lg-2">
                <p style="border-left: 25px solid #ffc107; padding-left: 20px;">Late</p>
            </div>
            <div class="col-lg-2">
                <p style="border-left: 25px solid #dc3545; padding-left: 20px;">Absent</p>
            </div>
            <div class="col-lg-2">
                <p style="border-left: 25px solid #28a745; padding-left: 20px;">On Leave</p>
            </div>
        </div>
        <table class="table table-hover table-sm" id="attendance-table">
            <thead>
                <th>Date</th>
                <th>Morning In</th>
                <th>Morning Out</th>
                <th>Afternoon In</th>
                <th>Afternoon Out</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        function fetchAttendance(month, year) {
            var date = year + '-' + month
            $.ajax({
                url : "{{ route('employees.get-attendance') }}",
                type : 'GET',
                data :  {
                    month : date,
                    employe_id : "{{ $employee->id }}"
                },
                success : function(res) {                    
                    $('#attendance-table tbody tr').remove();
                    $.each(res, function(index, element) {
                        $('#attendance-table tbody').append(
                            '<tr>' +
                                '<td>' + res[index]['Date'] + '</td>' +
                                '<td ' + (res[index]['HasLeave']=='Yes' ? 'class="bg-success"' : (jQuery.isEmptyObject(res[index]['MorningIn']) ? 'class="bg-danger"' : '')) + '>' + res[index]['MorningIn'] + '</td>' +
                                '<td ' + (res[index]['HasLeave']=='Yes' ? 'class="bg-success"' : (jQuery.isEmptyObject(res[index]['MorningOut']) ? 'class="bg-danger"' : '')) + '>' + res[index]['MorningOut'] + '</td>' +
                                '<td ' + (res[index]['HasLeave']=='Yes' ? 'class="bg-success"' : (jQuery.isEmptyObject(res[index]['AfternoonIn']) ? 'class="bg-danger"' : '')) + '>' + res[index]['AfternoonIn'] + '</td>' +
                                '<td ' + (res[index]['HasLeave']=='Yes' ? 'class="bg-success"' : (jQuery.isEmptyObject(res[index]['AfternoonOut']) ? 'class="bg-danger"' : '')) + '>' + res[index]['AfternoonOut'] + '</td>' +
                            '</tr>'
                        );
                    })
                },
                error : function(err) {
                    alert('Error fetching biometric attendance')
                }
            })
        }
        $(document).ready(function() {
            fetchAttendance($('#month').val(), $('#year').val())

            $('#month').on('change', function() {
                fetchAttendance($('#month').val(), $('#year').val())
            })

            $('#year').on('change', function() {
                fetchAttendance($('#month').val(), $('#year').val())
            })
        })
    </script>
@endpush