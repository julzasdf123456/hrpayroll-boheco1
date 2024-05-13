@php
    use App\Models\Employees;
    use App\Models\TripTickets;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-6">
                    <h4>
                        Trip Ticket GRS Requests
                    </h4>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('tripTicketGRS.all-grs') }}" class="float-right btn btn-primary-skinny">View All GRS</a>
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
                                    <tr id="{{ $item->id }}" style="cursor: pointer;">
                                        <td onclick="expand(`{{ $item->id }}`)">{{ $item->id }}</td>
                                        <td onclick="expand(`{{ $item->id }}`)">{{ date('F d, Y', strtotime($item->DateOfTravel)) }}</td>
                                        <td onclick="expand(`{{ $item->id }}`)">{{ Employees::getMergeName($item) }}</td>
                                        <td onclick="expand(`{{ $item->id }}`)">{{ Employees::getDriverMergeName($item) }}</td>
                                        <td onclick="expand(`{{ $item->id }}`)">{{ $item->Vehicle }}</td>
                                        <td onclick="expand(`{{ $item->id }}`)"><span class="badge {{ TripTickets::getBgStatus($item->Status) }}">{{ $item->Status }}</span></td>
                                        <td>
                                            
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
@include('trip_ticket_g_rs.modal_issue_grs')

@push('page_scripts')
    <script>
        $(document).ready(function() {
           
        })   
        
    </script>
@endpush
