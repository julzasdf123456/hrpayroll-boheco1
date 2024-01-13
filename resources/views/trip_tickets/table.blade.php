<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="trip-tickets-table">
            <thead>
            <tr>
                <th>Datetimefiled</th>
                <th>Employeeid</th>
                <th>Purposeoftravel</th>
                <th>Driver</th>
                <th>Status</th>
                <th>Datetimedeparted</th>
                <th>Datetimearrived</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tripTickets as $tripTicket)
                <tr>
                    <td>{{ $tripTicket->DatetimeFiled }}</td>
                    <td>{{ $tripTicket->EmployeeId }}</td>
                    <td>{{ $tripTicket->PurposeOfTravel }}</td>
                    <td>{{ $tripTicket->Driver }}</td>
                    <td>{{ $tripTicket->Status }}</td>
                    <td>{{ $tripTicket->DatetimeDeparted }}</td>
                    <td>{{ $tripTicket->DatetimeArrived }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['tripTickets.destroy', $tripTicket->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('tripTickets.show', [$tripTicket->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('tripTickets.edit', [$tripTicket->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $tripTickets])
        </div>
    </div>
</div>
