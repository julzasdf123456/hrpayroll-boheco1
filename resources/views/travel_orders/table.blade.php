<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="travel-orders-table">
            <thead>
            <tr>
                <th>Datefiled</th>
                <th>Destination</th>
                <th>Purpose</th>
                <th>Userid</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($travelOrders as $travelOrders)
                <tr>
                    <td>{{ $travelOrders->DateFiled }}</td>
                    <td>{{ $travelOrders->Destination }}</td>
                    <td>{{ $travelOrders->Purpose }}</td>
                    <td>{{ $travelOrders->UserId }}</td>
                    <td>{{ $travelOrders->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['travelOrders.destroy', $travelOrders->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('travelOrders.show', [$travelOrders->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('travelOrders.edit', [$travelOrders->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $travelOrders])
        </div>
    </div>
</div>
