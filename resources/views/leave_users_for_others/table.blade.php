<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="leave-users-for-others-table">
            <thead>
            <tr>
                <th>Leavecreator</th>
                <th>Employeeid</th>
                <th>Department</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($leaveUsersForOthers as $leaveUsersForOthers)
                <tr>
                    <td>{{ $leaveUsersForOthers->LeaveCreator }}</td>
                    <td>{{ $leaveUsersForOthers->EmployeeId }}</td>
                    <td>{{ $leaveUsersForOthers->Department }}</td>
                    <td>{{ $leaveUsersForOthers->Status }}</td>
                    <td>{{ $leaveUsersForOthers->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['leaveUsersForOthers.destroy', $leaveUsersForOthers->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('leaveUsersForOthers.show', [$leaveUsersForOthers->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('leaveUsersForOthers.edit', [$leaveUsersForOthers->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $leaveUsersForOthers])
        </div>
    </div>
</div>
