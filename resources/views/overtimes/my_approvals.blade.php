@php
    use App\Models\Employees;
    use App\Models\Users;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Overtime Authorization Approvals</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Employee</th>
                            <th>Purpose</th>
                            <th>Start Date</th>
                            <th>Start Time</th>
                            <th>End Date</th>
                            <th>End Time</th>
                            <th>Max Hours</th>
                            <th>Total Hours</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($overtimes as $item)
                                <tr id="{{ $item->id }}" max-hours="{{ $item->MaxHourThreshold }}" data-date-start="{{ $item->DateOfOT }}" data-date-end="{{ $item->DateOTEnded }}">
                                    <td onclick="showDetails(`{{ $item->id }}`)">{{ Employees::getMergeName($item) }}</td>
                                    <td onclick="showDetails(`{{ $item->id }}`)">{{ $item->PurposeOfOT }}</td>
                                    <td onclick="showDetails(`{{ $item->id }}`)">{{ date('M d, Y', strtotime($item->DateOfOT)) }}</td>
                                    <td>
                                        <input style='width: 80%; display: inline;' type='time' class='form-control form-control-sm' id='start-{{ $item->id }}' value="{{ $item->From != null ? date('H:i:s', strtotime($item->From)) : '' }}"/>
                                        <button title='Fetch from biometrics' onclick='fetchFromBio(`{{ $item->id }}`, `IN`, `{{ $item->BiometricsUserId }}`, `{{ $item->DateOfOT }}`)' class='btn btn-sm float-right btn-link'><i class='fas fa-fingerprint text-info'></i></button>
                                    </td>
                                    <td onclick="showDetails(`{{ $item->id }}`)">{{ date('M d, Y', strtotime($item->DateOTEnded)) }}</td>
                                    <td>
                                        <input style='width: 80%; display: inline;' type='time' class='form-control form-control-sm' id='end-{{ $item->id }}' value="{{ $item->To != null ? date('H:i:s', strtotime($item->To)) : '' }}"/>
                                        <button title='Fetch from biometrics' onclick='fetchFromBio(`{{ $item->id }}`, `OUT`, `{{ $item->BiometricsUserId }}`, `{{ $item->DateOTEnded }}`)' class='btn btn-sm float-right btn-link'><i class='fas fa-fingerprint text-info'></i></button>
                                    </td>
                                    <td onclick="showDetails(`{{ $item->id }}`)">{{ $item->MaxHourThreshold }}</td>
                                    <td id="total-hrs-{{ $item->id }}" onclick="showDetails(`{{ $item->id }}`)">{{ $item->TotalHours }}</td>
                                    <td>
                                        <button title="Reject" onclick="reject(`{{ $item->id }}`)" class="btn btn-sm btn-danger float-right" style="margin-left: 5px;"><i class="fas fa-times-circle"></i></button>
                                        <button title="Approve" onclick="approve(`{{ $item->id }}`)" class="btn btn-sm btn-success float-right"><i class="fas fa-check-circle"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('overtimes.modal_view_full')

@include('overtimes.modal_fetch_from_bio')

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function approve(id) {
            Swal.fire({
                title: 'Confirm Approval',
                text : 'Approve this overtime application?',
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: `Cancel`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('overtimes.approve') }}",
                        type : "GET",
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Overtime approved!'
                            })
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error approving overtime!'
                            })
                        }
                    })
                } 
            })
        }

        function reject(id) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Remarks/Notes',
                    inputPlaceholder: 'Type your remarks here...',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Reject This Overtime?',
                    text : 'Before you reject this overtime, please provide a remark or comment so the employee can assess the situation further.',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : "{{ route('overtimes.reject') }}",
                        type : "GET",
                        data : {
                            id : id,
                            Notes : text,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'info',
                                text : 'Overtime rejected!'
                            })
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error rejecting overtime!'
                            })
                        }
                    })
                }
            })()
        }

        function showDetails(id) {
            $('#modal-ot-view').modal('show')

            $.ajax({
                url : "{{ route('overtimes.get-overtime-ajax') }}",
                type : "GET",
                data : {
                    id : id,
                },
                success : function(res) {
                    $('#ot-employee').text(serializeEmployeeName(res['FirstName'], res['LastName'], res['MiddleName'], res['Suffix']))
                    $('#ot-purpose').text(res['PurposeOfOT'])
                    $('#ot-leave-type').text(res['TypeOfDay'])
                    $('#ot-start-date').text(isNull(res['DateOfOT']) ? '-' : moment(res['DateOfOT']).format('MMM DD, YYYY'))
                    $('#ot-start-time').text(isNull(res['From']) ? '-' : res['From'].split('.')[0])
                    $('#ot-end-date').text(isNull(res['DateOTEnded']) ? '-' : moment(res['DateOTEnded']).format('MMM DD, YYYY'))
                    $('#ot-end-time').text(isNull(res['To']) ? '-' : res['To'].split('.')[0])
                    $('#ot-max-hours').text(res['MaxHourThreshold'])
                    $('#ot-total-hours').text(res['TotalHours'])
                    $('#ot-prepared-by').text(res['name'])
                    $('#ot-date-prepared').text(moment(res['created_at']).format('MMM DD, YYYY hh:mm A'))
                    $('#ot-notes').text(res['Notes'])

                    $('#ot-status').removeClass('bg-warning').removeClass('bg-success')
                    if (res['Status'] === null | isNull(res['Status'])) {
                        $('#ot-status').addClass('bg-warning')
                        $('#ot-status').text('PENDING APPROVAL')
                    } else {
                        $('#ot-status').addClass('bg-success')
                        $('#ot-status').text(res['Status'])
                    }

                    // SIGNATORY LOGS
                    var signatoryLogs = res['Logs']
                    $('.ot-log').remove()
                    if (!isNull(signatoryLogs)) {
                        $('#ot-logs').html(addLogsRow(signatoryLogs)) // FOUND IN overtimes.modal_view_full
                    }
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting overtime data'
                    })
                }
            })
        }

        function insertTime(timestamp, type, index) {
            if (type === 'IN') {
                $('#start-' + index).val(moment(timestamp).format('HH:mm'))
            } else if (type === 'OUT') {
                $('#end-' + index).val(moment(timestamp).format('HH:mm'))
            }

            // UPDATE TOTAL HOURS
            // items[objIndex].TotalHours = getTotalHours(index)
            // // SHOW TOTAL HOURS
            $('#total-hrs-' + index).text(getTotalHours(index))

            $('#modal-fetch-from-bio').modal('hide')
        }

        function getTotalHours(index) {

            var startTime = $('#start-' + index).val()
            var endTime = $('#end-' + index).val()

            if (isNull(startTime) | isNull(endTime)) {
                return null
            } else {
                var start = moment($('#' + index).attr('data-date-start') + " " + startTime)
                var end = moment($('#' + index).attr('data-date-end') + " " + endTime)

                var mins = end.diff(start, 'minutes')
                
                var totalHrs = Math.round(((mins / 60) + Number.EPSILON) * 100) / 100 // returns hours

                var maxHrs = parseFloat($('#' + index).attr('max-hours'))

                if (totalHrs <= maxHrs) {
                    return totalHrs
                } else {
                    return maxHrs
                }
            }
        }
    </script>
@endpush