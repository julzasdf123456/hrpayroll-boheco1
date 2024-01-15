@php
    use App\Models\Employees;
    use App\Models\TripTickets;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>
                        My Trip Ticket Approvals
                    </h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-sm btn-primary float-right" href="{{ route('tripTickets.create') }}"><i class="fas fa-plus-circle ico-tab-mini"></i>File New Trip Ticket</a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-car ico-tab"></i>Trip tickets filed</span>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <th>Trip ID</th>
                                <th>Date of Travel</th>
                                <th>Requisitioner</th>
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
                                        <td onclick="expand(`{{ $item->id }}`)">{{ Employees::getMergeName($item) }}</td>
                                        <td onclick="expand(`{{ $item->id }}`)">{{ Employees::getDriverMergeName($item) }}</td>
                                        <td onclick="expand(`{{ $item->id }}`)">{{ $item->Vehicle }}</td>
                                        <td onclick="expand(`{{ $item->id }}`)"><span class="badge {{ TripTickets::getBgStatus($item->Status) }}">{{ $item->Status }}</span></td>
                                        <td>
                                            {{-- REQUEST GRS IF NOT REJECTED --}}
                                            @if ($item->Status != 'REJECTED') 
                                                @if ($item->RequestGRS == 'Yes')
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
                    </div>
                    <div class="card-footer">
                        {{ $tripTickets->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

{{-- CALLS expand(`id`) METHOD TO SHOW MODAL --}}
{{-- id = TripTicket id --}}
@include('trip_tickets.modal_show_trip_ticket')

@push('page_scripts')
    <script>
        $(document).ready(function() {
           
        })   
        
        function approve() {
            Swal.fire({
                title: "Confirm Approval?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3273a8",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('tripTickets.approve-leave') }}",
                        type : "GET",
                        data : {
                            id : ttId, // ttId is found in `modal_show_trip_ticket.blade.php`
                        },
                        success : function(res) {
                            $('#modal-trip-ticket-details').modal('hide')
                            Toast.fire({
                                icon : 'success',
                                text : 'Trip ticket approved'
                            })
                            $('#' + ttId).remove()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error trip ticket approval!'
                            })
                        }
                    })
                }
            })
        }

        function reject() {
            Swal.fire({
                title: "Reject Trip Ticket?",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "#3273a8",
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('tripTickets.reject-leave') }}",
                        type : "GET",
                        data : {
                            id : ttId, // ttId is found in `modal_show_trip_ticket.blade.php`
                        },
                        success : function(res) {
                            $('#modal-trip-ticket-details').modal('hide')
                            Toast.fire({
                                icon : 'info',
                                text : 'Trip ticket rejected'
                            })
                            $('#' + ttId).remove()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error trip ticket rejection!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush
