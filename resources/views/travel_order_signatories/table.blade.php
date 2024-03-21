<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="travel-order-signatories-table">
            <thead>
            <tr>
                <th>Travelorderid</th>
                <th>Userid</th>
                <th>Rank</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($travelOrderSignatories as $travelOrderSignatories)
                <tr>
                    <td>{{ $travelOrderSignatories->TravelOrderId }}</td>
                    <td>{{ $travelOrderSignatories->UserId }}</td>
                    <td>{{ $travelOrderSignatories->Rank }}</td>
                    <td>{{ $travelOrderSignatories->Status }}</td>
                    <td>{{ $travelOrderSignatories->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['travelOrderSignatories.destroy', $travelOrderSignatories->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('travelOrderSignatories.show', [$travelOrderSignatories->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('travelOrderSignatories.edit', [$travelOrderSignatories->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $travelOrderSignatories])
        </div>
    </div>
</div>
