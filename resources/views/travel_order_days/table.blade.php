<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="travel-order-days-table">
            <thead>
            <tr>
                <th>Travelorderid</th>
                <th>Day</th>
                <th>Longevity</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($travelOrderDays as $travelOrderDays)
                <tr>
                    <td>{{ $travelOrderDays->TravelOrderId }}</td>
                    <td>{{ $travelOrderDays->Day }}</td>
                    <td>{{ $travelOrderDays->Longevity }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['travelOrderDays.destroy', $travelOrderDays->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('travelOrderDays.show', [$travelOrderDays->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('travelOrderDays.edit', [$travelOrderDays->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $travelOrderDays])
        </div>
    </div>
</div>
