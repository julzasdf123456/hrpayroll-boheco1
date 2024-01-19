<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="attendane-confirmations-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Reason</th>
                <th>Amin</th>
                <th>Amout</th>
                <th>Pmin</th>
                <th>Pmout</th>
                <th>Otin</th>
                <th>Otout</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($attendaneConfirmations as $attendaneConfirmations)
                <tr>
                    <td>{{ $attendaneConfirmations->EmployeeId }}</td>
                    <td>{{ $attendaneConfirmations->Reason }}</td>
                    <td>{{ $attendaneConfirmations->AMIn }}</td>
                    <td>{{ $attendaneConfirmations->AMOut }}</td>
                    <td>{{ $attendaneConfirmations->PMIn }}</td>
                    <td>{{ $attendaneConfirmations->PMOut }}</td>
                    <td>{{ $attendaneConfirmations->OTIn }}</td>
                    <td>{{ $attendaneConfirmations->OTOut }}</td>
                    <td>{{ $attendaneConfirmations->Status }}</td>
                    <td>{{ $attendaneConfirmations->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['attendaneConfirmations.destroy', $attendaneConfirmations->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('attendaneConfirmations.show', [$attendaneConfirmations->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('attendaneConfirmations.edit', [$attendaneConfirmations->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $attendaneConfirmations])
        </div>
    </div>
</div>
