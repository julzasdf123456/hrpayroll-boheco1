<div class="table-responsive">
    <table class="table" id="leaveDays-table">
        <thead>
            <tr>
                <th>Leaveid</th>
        <th>Leavedate</th>
        <th>Longevity</th>
        <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveDays as $leaveDays)
            <tr>
                <td>{{ $leaveDays->LeaveId }}</td>
            <td>{{ $leaveDays->LeaveDate }}</td>
            <td>{{ $leaveDays->Longevity }}</td>
            <td>{{ $leaveDays->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveDays.destroy', $leaveDays->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveDays.show', [$leaveDays->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('leaveDays.edit', [$leaveDays->id]) }}" class='btn btn-default btn-xs'>
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
