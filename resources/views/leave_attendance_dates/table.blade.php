<div class="table-responsive">
    <table class="table" id="leaveAttendanceDates-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Dateofleave</th>
        <th>Leaveid</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveAttendanceDates as $leaveAttendanceDates)
            <tr>
                <td>{{ $leaveAttendanceDates->EmployeeId }}</td>
            <td>{{ $leaveAttendanceDates->DateOfLeave }}</td>
            <td>{{ $leaveAttendanceDates->LeaveId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveAttendanceDates.destroy', $leaveAttendanceDates->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveAttendanceDates.show', [$leaveAttendanceDates->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('leaveAttendanceDates.edit', [$leaveAttendanceDates->id]) }}" class='btn btn-default btn-xs'>
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
