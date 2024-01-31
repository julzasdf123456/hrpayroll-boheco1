@php
    use App\Models\Overtimes;
@endphp

<table class="table table-hover">
    <thead>
        <th class="text-center">Date/Time<br>Start</th>
        <th class="text-center">Date/Time<br>End</th>
        <th class="text-center">OT/Day Type</th>
        <th class="text-center">Max Hours<br>Allowed</th>
        <th class="text-center">Actual<br>Hours</th>
        <th class="text-center">Status</th>
    </thead>
    <tbody>
        @foreach ($overtimes as $item)
            <tr>
                <td onclick="showDetails(`{{ $item->id }}`)">{{ date('M d, Y', strtotime($item->DateOfOT)) }} {{ date('h:i A', strtotime($item->From)) }}</td>
                <td onclick="showDetails(`{{ $item->id }}`)">{{ date('M d, Y', strtotime($item->DateOTEnded)) }} {{ date('h:i A', strtotime($item->To)) }}</td>
                <td onclick="showDetails(`{{ $item->id }}`)">{{ $item->TypeOfDay }}</td>
                <td onclick="showDetails(`{{ $item->id }}`)">{{ $item->MaxHourThreshold }} hrs</td>
                <td onclick="showDetails(`{{ $item->id }}`)">{{ $item->TotalHours }} hrs</td>
                <td onclick="showDetails(`{{ $item->id }}`)">
                    <span class="badge {{ Overtimes::getStatusColor($item->Status) }}">
                        {{ $item->Status }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('overtimes.modal_view_full')

@push('page_scripts')
    <script>
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

                    $('#ot-status').removeClass('bg-warning').removeClass('bg-success').removeClass('bg-danger')
                    $('#ot-status').addClass(getStatusColor(res['Status']))
                    if (res['Status'] === null | isNull(res['Status'])) {
                        $('#ot-status').text('PENDING APPROVAL')
                    } else {
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
    </script>
@endpush