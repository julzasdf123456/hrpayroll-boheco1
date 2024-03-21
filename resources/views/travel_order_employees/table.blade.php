<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="travel-order-employees-table">
            <thead>
            <tr>
                <th>Travelorderid</th>
                <th>Employeeid</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($travelOrderEmployees as $travelOrderEmployees)
                <tr>
                    <td>{{ $travelOrderEmployees->TravelOrderId }}</td>
                    <td>{{ $travelOrderEmployees->EmployeeId }}</td>
                    <td>{{ $travelOrderEmployees->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['travelOrderEmployees.destroy', $travelOrderEmployees->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('travelOrderEmployees.show', [$travelOrderEmployees->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('travelOrderEmployees.edit', [$travelOrderEmployees->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $travelOrderEmployees])
        </div>
    </div>
</div>
