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
                    <div class="col-lg-2 col-md-6">
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
                    <div class="col-lg-1 col-md-6">
                        <label for="MaxHours">Max Hours</label>
                        <input type="number" step="any" id="MaxHours" class="form-control text-right" value="4">
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
                    <div class="col-lg-12 table-responsive" style="margin-top: 16px;">
                        <table class="table table-hover table-bordered" id="queue-table">
                            <thead>
                                <th>Employee</th>
                                <th>Purpose</th>
                                <th>Start Date</th>
                                <th>Start Time</th>
                                <th>End Date</th>
                                <th>End Time</th>
                                <th>Leave Day Type</th>
                                <th>Max Hours</th>
                                <th>Total Hours</th>
                                <th></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success disabled" id="save-btn"><i class="fas fa-check-circle ico-tab-mini"></i>Submit Request</button>
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
            $('body').addClass('sidebar-collapse')

            $('#save-btn').on('click', function() {
                if (isNull(items)) {
                    Toast.fire({
                        icon : 'info',
                        text : 'Add employees first'
                    })
                } else {
                    Swal.fire({
                        title: "Confirm Overtime Authorization Request?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#3273a8",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url : "{{ route('overtimes.save') }}",
                                type : "GET",
                                data : {
                                    Data : items,
                                },
                                success : function(res) {
                                    Toast.fire({
                                        icon : 'success',
                                        text : 'Offset filing success!'
                                    })
                                    window.location.href = "{{ route('home') }}"
                                },
                                error : function(err) {
                                    Swal.fire({
                                        icon : 'error',
                                        text : 'An error occurred during filing of offset'
                                    })
                                }
                            })
                        }
                    })
                }
            })
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
            var maxHours = isNull($('#MaxHours').val()) ? null : parseFloat($('#MaxHours').val())

            if (isNull(employeeId) | isNull(purpose) | isNull(startDate) | isNull(endDate) | isNull(typeOfLeaveValue)) {
                Toast.fire({
                    icon : 'info',
                    text : 'Please fill in all fields'
                })
            } else {
                $('#queue-table tbody').append(addRowToQueue(index, employeeId, employeeName, purpose, startDate, endDate, typeOfLeave, bioId, maxHours))
                items.push({
                    Index : index,
                    EmployeeId : employeeId,
                    EmployeeName : employeeName,
                    Purpose : purpose,
                    StartDate : startDate,
                    StartTime : null,
                    EndDate : endDate,
                    EndTime : null,
                    TypeOfLeave : typeOfLeave,
                    Multiplier : typeOfLeaveValue,
                    MaxHours : maxHours,
                    TotalHours : null,
                    BioId : bioId,
                })
                index++
            }
        }

        function addRowToQueue(index, employeeId, employeeName, purpose, startDate, endDate, typeOfLeave, bioId, maxHours) {
            // fetchFromBio() is at overtimes.modal_fetch_from_bio
            return "<tr id='" + index + "'>" +
                        "<td>" + employeeName + "</td>" +
                        "<td>" + purpose + "</td>" +
                        "<td>" + startDate + "</td>" +
                        "<td>" + 
                            "<input onchange='inputChangeTime(`IN`, `" + index + "`, `" + startDate + "`)' style='width: 80%; display: inline;' type='time' class='form-control form-control-sm' id='start-" + index + "'/>" +
                            "<button title='Fetch from biometrics' onclick='fetchFromBio(`" + index + "`, `IN`, `" + bioId + "`, `" + startDate + "`)' class='btn btn-sm float-right btn-link'><i class='fas fa-fingerprint text-info'></i></button>" +
                        "</td>" +
                        "<td>" + endDate + "</td>" +
                        "<td>" + 
                            "<input onchange='inputChangeTime(`OUT`, `" + index + "`, `" + endDate + "`)' style='width: 80%; display: inline;' type='time' class='form-control form-control-sm' id='end-" + index + "'/>" +
                            "<button title='Fetch from biometrics' onclick='fetchFromBio(`" + index + "`, `OUT`, `" + bioId + "`, `" + endDate + "`)' class='btn btn-sm float-right btn-link'><i class='fas fa-fingerprint text-info'></i></button>" +
                        "</td>" +
                        "<td>" + typeOfLeave + "</td>" +
                        "<td>" + maxHours + "</td>" +
                        "<td id='total-hrs-" + index + "'></td>" +
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

        function inputChangeTime(type, index, date) {
            var start = $('#start-' + index).val()
            var end = $('#end-' + index).val()

            if(type === 'IN' && !isNull(start)) {
                insertTime(date + ' ' + start, type, index)
            } 
            
            if (type === 'OUT' && !isNull(end)) {
                insertTime(date + ' ' + end, type, index)
            }            
        }

        function insertTime(timestamp, type, index) {
            // UPDATE TIME VALUES IN items[]
            objIndex = items.findIndex((obj => obj.Index == index))

            if (type === 'IN') {
                $('#start-' + index).val(moment(timestamp).format('HH:mm'))
                items[objIndex].StartTime = moment(timestamp).format('HH:mm')
            } else if (type === 'OUT') {
                $('#end-' + index).val(moment(timestamp).format('HH:mm'))
                items[objIndex].EndTime = moment(timestamp).format('HH:mm')
            }

            // UPDATE TOTAL HOURS
            items[objIndex].TotalHours = getTotalHours(index)
            // SHOW TOTAL HOURS
            $('#total-hrs-' + index).text(items[objIndex].TotalHours)

            $('#modal-fetch-from-bio').modal('hide')
        }

        function getTotalHours(index) {
            objIndex = items.findIndex((obj => obj.Index == index))

            var startTime = items[objIndex].StartTime
            var endTime = items[objIndex].EndTime

            if (isNull(startTime) | isNull(endTime)) {
                return null
            } else {
                var start = moment(items[objIndex].StartDate + " " + startTime)
                var end = moment(items[objIndex].EndDate + " " + endTime)

                var mins = end.diff(start, 'minutes')
                
                var totalHrs = Math.round(((mins / 60) + Number.EPSILON) * 100) / 100 // returns hours

                var maxHrs = items[objIndex].MaxHours

                if (totalHrs <= maxHrs) {
                    return totalHrs
                } else {
                    return maxHrs
                }
            }
        }
    </script>
@endpush
