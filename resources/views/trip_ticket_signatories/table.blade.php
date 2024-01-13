<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="trip-ticket-signatories-table">
            <thead>
            <tr>
                <th>Tripticketid</th>
                <th>Employeeid</th>
                <th>Rank</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tripTicketSignatories as $tripTicketSignatories)
                <tr>
                    <td>{{ $tripTicketSignatories->TripTicketId }}</td>
                    <td>{{ $tripTicketSignatories->EmployeeId }}</td>
                    <td>{{ $tripTicketSignatories->Rank }}</td>
                    <td>{{ $tripTicketSignatories->Status }}</td>
                    <td>{{ $tripTicketSignatories->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['tripTicketSignatories.destroy', $tripTicketSignatories->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('tripTicketSignatories.show', [$tripTicketSignatories->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('tripTicketSignatories.edit', [$tripTicketSignatories->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $tripTicketSignatories])
        </div>
    </div>
</div>
