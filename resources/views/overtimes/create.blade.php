@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Create Overtime Authorization</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card shadow-none">
            <div class="card-body">
                <div class="row">
                    {{-- FORM --}}
                    <div class="col-lg-3 col-md-6">
                        <label for="EmployeeId">Employee</label>
                        <select class="custom-select select2"  name="EmployeeId" id="EmployeeId" style="width: 100%;" required>
                            <option value="">-- Select --</option>
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}" {{ Auth::user()->employee_id==$item->id ? 'selected' : '' }} bio-id="{{ $item->BiometricsUserId }}">{{ Employees::getMergeNameFormal($item) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="Purpose">Purpose</label>
                        <input type="text" id="Purpose" class="form-control">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="DateFrom">Start Date</label>
                        <input type="text" name="DateFrom" id="DateFrom" class="form-control" autofocus required/>
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#DateFrom').datetimepicker({
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
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="DateTo">End Date</label>
                        <input type="text" name="DateTo" id="DateTo" class="form-control" autofocus required/>
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#DateTo').datetimepicker({
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
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="TypeOfLeave">Type of Leave</label>
                        <select class="form-control" name="TypeOfLeave" id="TypeOfLeave">
                            <option value="">-- Select --</option>
                            <option value="1.25">Extra</option>
                            <option value="1.25">Ordinary</option>
                            <option value="1.25">Offday</option>
                            <option value="2">Holiday</option>
                        </select>
                    </div>
                    <div class="col-lg-12" style="margin-top: 5px;">
                        <button onclick="addToQueue()" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus-circle ico-tab-mini"></i>Add To Queue</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-none">
            <div class="card-body">
                <div class="row">
                    {{-- LIST --}}
                    <div class="col-lg-12" style="margin-top: 16px;">
                        <table class="table table-hover table-bordered" id="queue-table">
                            <thead>
                                <th>Employee</th>
                                <th>Purpose</th>
                                <th>Start Date</th>
                                <th>Start Time</th>
                                <th>End Date</th>
                                <th>End Time</th>
                                <th>Leave Day Type</th>
                                <th></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success disabled" id="save-btn"><i class="fas fa-check-circle ico-tab"></i>Save Request</button>
            </div>
        </div>
    </div>
@endsection

@include('overtimes.modal_fetch_from_bio')

@push('page_scripts')
    <script>
        var index = 0
        var items = []
        $(document).ready(function() {

        })

        function addToQueue() {
            $('#save-btn').removeClass('disabled')
            var employeeId = $('#EmployeeId').val()
            var employeeName = $("#EmployeeId option:selected").text()
            var bioId = $("#EmployeeId option:selected").attr('bio-id')
            var purpose = $('#Purpose').val()
            var startDate = $('#DateFrom').val()
            var endDate = $('#DateTo').val()
            var typeOfLeave = $('#TypeOfLeave option:selected').text()
            var typeOfLeaveValue = $('#TypeOfLeave').val()

            if (isNull(employeeId) | isNull(purpose) | isNull(startDate) | isNull(endDate) | isNull(typeOfLeave)) {
                Toast.fire({
                    icon : 'info',
                    text : 'Please fill in all fields'
                })
            } else {
                $('#queue-table tbody').append(addRowToQueue(index, employeeId, employeeName, purpose, startDate, endDate, typeOfLeave, bioId))
                items.push({
                    Index : index,
                    EmployeeId : employeeId,
                    EmployeeName : employeeName,
                    Purpose : purpose,
                    StartDate : startDate,
                    EndDate : endDate,
                    TypeOfLeave : typeOfLeave,
                    TypeOfLeaveValue : typeOfLeaveValue,
                    BioId : bioId,
                })
                index++
            }
        }

        function addRowToQueue(index, employeeId, employeeName, purpose, startDate, endDate, typeOfLeave, bioId) {
            // fetchFromBio() is at overtimes.modal_fetch_from_bio
            return "<tr id='" + index + "'>" +
                        "<td>" + employeeName + "</td>" +
                        "<td>" + purpose + "</td>" +
                        "<td>" + startDate + "</td>" +
                        "<td>" + 
                            "<input style='width: 80%; display: inline;' type='time' class='form-control form-control-sm' id='start-" + index + "'/>" +
                            "<button title='Fetch from biometrics' onclick='fetchFromBio(`" + index + "`, `IN`, `" + bioId + "`, `" + startDate + "`)' class='btn btn-sm float-right btn-link'><i class='fas fa-fingerprint text-info'></i></button>" +
                        "</td>" +
                        "<td>" + endDate + "</td>" +
                        "<td>" + 
                            "<input style='width: 80%; display: inline;' type='time' class='form-control form-control-sm' id='end-" + index + "'/>" +
                            "<button title='Fetch from biometrics' onclick='fetchFromBio(`" + index + "`, `OUT`, `" + bioId + "`, `" + endDate + "`)' class='btn btn-sm float-right btn-link'><i class='fas fa-fingerprint text-info'></i></button>" +
                        "</td>" +
                        "<td>" + typeOfLeave + "</td>" +
                        "<td><button onclick='removeFromQueue(`" + index + "`)' class='btn btn-sm float-right btn-link'><i class='fas fa-trash text-danger'></i></button></td>" +
                    "</tr>"
        }

        function removeFromQueue(index) {
            $('#' + index).remove()

            // remove from items
            items = items.filter(function (obj) { 
                return obj.Index != index
            })

            if (items.length == 0) {                
                $('#save-btn').addClass('disabled')
            }
        }

        function insertTime(timestamp, type, index) {
            if (type === 'IN') {
                $('#start-' + index).val(moment(timestamp).format('HH:mm'))
            } else if (type === 'OUT') {
                $('#end-' + index).val(moment(timestamp).format('HH:mm'))
            }
            $('#modal-fetch-from-bio').modal('hide')
        }
    </script>
@endpush
