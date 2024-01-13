<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="trip-ticket-destinations-table">
            <thead>
            <tr>
                <th>Tripticketid</th>
                <th>Destinationaddress</th>
                <th>Barangayid</th>
                <th>Townid</th>
                <th>Employeeid</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tripTicketDestinations as $tripTicketDestinations)
                <tr>
                    <td>{{ $tripTicketDestinations->TripTicketId }}</td>
                    <td>{{ $tripTicketDestinations->DestinationAddress }}</td>
                    <td>{{ $tripTicketDestinations->BarangayId }}</td>
                    <td>{{ $tripTicketDestinations->TownId }}</td>
                    <td>{{ $tripTicketDestinations->EmployeeId }}</td>
                    <td>{{ $tripTicketDestinations->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['tripTicketDestinations.destroy', $tripTicketDestinations->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('tripTicketDestinations.show', [$tripTicketDestinations->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('tripTicketDestinations.edit', [$tripTicketDestinations->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $tripTicketDestinations])
        </div>
    </div>
</div>
