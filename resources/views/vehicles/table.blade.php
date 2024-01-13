<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="vehicles-table">
            <thead>
            <tr>
                <th>Vehiclename</th>
                <th>Platenumber</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->VehicleName }}</td>
                    <td>{{ $vehicle->PlateNumber }}</td>
                    <td>{{ $vehicle->Brand }}</td>
                    <td>{{ $vehicle->Model }}</td>
                    <td>{{ $vehicle->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['vehicles.destroy', $vehicle->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('vehicles.show', [$vehicle->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('vehicles.edit', [$vehicle->id]) }}"
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
            {{ $vehicles->links() }}
        </div>
    </div>
</div>
