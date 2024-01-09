<div class="table-responsive">
    <table class="table" id="attendanceDatas-table">
        <thead>
            <tr>
                <th>Biometricuserid</th>
        <th>Employeeid</th>
        <th>Userid</th>
        <th>Timestamp</th>
        <th>State</th>
        <th>Uid</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($attendanceDatas as $attendanceData)
            <tr>
                <td>{{ $attendanceData->BiometricUserId }}</td>
            <td>{{ $attendanceData->EmployeeId }}</td>
            <td>{{ $attendanceData->UserId }}</td>
            <td>{{ $attendanceData->Timestamp }}</td>
            <td>{{ $attendanceData->State }}</td>
            <td>{{ $attendanceData->UID }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['attendanceDatas.destroy', $attendanceData->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('attendanceDatas.show', [$attendanceData->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('attendanceDatas.edit', [$attendanceData->id]) }}" class='btn btn-default btn-xs'>
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
