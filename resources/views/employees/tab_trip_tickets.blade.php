@php
    use App\Models\Employees;
    use App\Models\TripTickets;
@endphp
<table class="table table-hover">
    <thead>
        <th>Trip ID</th>
        <th>Date of Travel</th>
        <th>Driver</th>
        <th>Vehicle</th>
        <th>Status</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($tripTickets as $item)
            <tr id="{{ $item->id }}">
                <td onclick="expand(`{{ $item->id }}`)">{{ $item->id }}</td>
                <td onclick="expand(`{{ $item->id }}`)">{{ date('F d, Y', strtotime($item->DateOfTravel)) }}</td>
                <td onclick="expand(`{{ $item->id }}`)">{{ Employees::getDriverMergeName($item) }}</td>
                <td onclick="expand(`{{ $item->id }}`)">{{ $item->Vehicle }}</td>
                <td onclick="expand(`{{ $item->id }}`)"><span class="badge {{ TripTickets::getBgStatus($item->Status) }}">{{ $item->Status }}</span></td>
                <td>
                    <button onclick="trash(`{{ $item->id }}`)" class="btn btn-link btn-xs float-right" title="Trash trip ticket"><i class="fas fa-trash text-danger"></i></button>
                    
                    @if ($item->Status != 'APPROVED')
                        <a href="{{ route('tripTickets.edit', [$item->id]) }}" class="btn btn-link btn-xs float-right" title="Edit trip ticket"><i class="fas fa-pen text-primary"></i></a>
                    @else
                        <a class="btn btn-link btn-xs float-right disabled" title="Edit trip ticket"><i class="fas fa-pen text-default"></i></a>
                    @endif  
                    
                    {{-- REQUEST GRS IF NOT REJECTED --}}
                    @if ($item->Status != 'REJECTED')
                        @if ($item->RequestGRS == null)
                            <button onclick="requestGRS(`{{ $item->id }}`)" class="btn btn-default btn-xs float-right"><i class="fas fa-gas-pump ico-tab-mini"></i>Request GRS</button>
                        @elseif ($item->RequestGRS == 'Yes')
                            <button class="btn btn-info btn-xs float-right"><i class="fas fa-gas-pump ico-tab-mini"></i>GRS Requested</button>
                        @elseif ($item->RequestGRS == 'Added')
                            <button class="btn btn-success btn-xs float-right"><i class="fas fa-gas-pump ico-tab-mini"></i>GRS Attached</button>
                        @endif
                    @endif 
                    
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('trip_tickets.modal_show_trip_ticket')

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })   
        
        function trash(id) {
            Swal.fire({
                title: "Move Trip Ticket to Trash?",
                text: "You can always restore this anytime.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3273a8",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ url('/tripTickets') }}/" + id,
                        type : "DELETE",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Trip ticket moved to trash'
                            })
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error deleting trip ticket!'
                            })
                        }
                    })
                }
            })
        }

        function requestGRS(id) {
            Swal.fire({
                title: "Request GRS?",
                text : "After requesting, proceed to the GRS officer for the printed GRS form.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3273a8",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('tripTickets.request-grs') }}",
                        type : "GET",
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'GRS Requested'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error requesting GRS!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush