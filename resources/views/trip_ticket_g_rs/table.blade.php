<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="trip-ticket-g-rs-table">
            <thead>
            <tr>
                <th>Tripticketid</th>
                <th>Purpose</th>
                <th>Totalmileage</th>
                <th>Totalliters</th>
                <th>Typeoffuel</th>
                <th>Carratio</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tripTicketGRs as $tripTicketGRS)
                <tr>
                    <td>{{ $tripTicketGRS->TripTicketId }}</td>
                    <td>{{ $tripTicketGRS->Purpose }}</td>
                    <td>{{ $tripTicketGRS->TotalMileage }}</td>
                    <td>{{ $tripTicketGRS->TotalLiters }}</td>
                    <td>{{ $tripTicketGRS->TypeOfFuel }}</td>
                    <td>{{ $tripTicketGRS->CarRatio }}</td>
                    <td>{{ $tripTicketGRS->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['tripTicketGRs.destroy', $tripTicketGRS->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('tripTicketGRs.show', [$tripTicketGRS->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('tripTicketGRs.edit', [$tripTicketGRS->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $tripTicketGRs])
        </div>
    </div>
</div>
