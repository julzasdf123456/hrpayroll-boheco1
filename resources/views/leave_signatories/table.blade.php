<div class="table-responsive">
    <table class="table" id="leaveSignatories-table">
        <thead>
            <tr>
                <th>Leaveid</th>
        <th>Employeeid</th>
        <th>Rank</th>
        <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveSignatories as $leaveSignatories)
            <tr>
                <td>{{ $leaveSignatories->LeaveId }}</td>
            <td>{{ $leaveSignatories->EmployeeId }}</td>
            <td>{{ $leaveSignatories->Rank }}</td>
            <td>{{ $leaveSignatories->Status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveSignatories.destroy', $leaveSignatories->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveSignatories.show', [$leaveSignatories->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('leaveSignatories.edit', [$leaveSignatories->id]) }}" class='btn btn-default btn-xs'>
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
