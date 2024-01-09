<div class="table-responsive">
    <table class="table" id="leaveBalances-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Vacation</th>
        <th>Sick</th>
        <th>Special</th>
        <th>Maternity</th>
        <th>Maternityforsolomother</th>
        <th>Paternity</th>
        <th>Soloparent</th>
        <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveBalances as $leaveBalances)
            <tr>
                <td>{{ $leaveBalances->EmployeeId }}</td>
            <td>{{ $leaveBalances->Vacation }}</td>
            <td>{{ $leaveBalances->Sick }}</td>
            <td>{{ $leaveBalances->Special }}</td>
            <td>{{ $leaveBalances->Maternity }}</td>
            <td>{{ $leaveBalances->MaternityForSoloMother }}</td>
            <td>{{ $leaveBalances->Paternity }}</td>
            <td>{{ $leaveBalances->SoloParent }}</td>
            <td>{{ $leaveBalances->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveBalances.destroy', $leaveBalances->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveBalances.show', [$leaveBalances->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('leaveBalances.edit', [$leaveBalances->id]) }}" class='btn btn-default btn-xs'>
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
