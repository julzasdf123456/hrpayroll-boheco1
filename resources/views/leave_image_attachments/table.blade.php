<div class="table-responsive">
    <table class="table" id="leaveImageAttachments-table">
        <thead>
            <tr>
                <th>Leaveid</th>
        <th>Heximage</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveImageAttachments as $leaveImageAttachments)
            <tr>
                <td>{{ $leaveImageAttachments->LeaveId }}</td>
            <td>{{ $leaveImageAttachments->HexImage }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveImageAttachments.destroy', $leaveImageAttachments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveImageAttachments.show', [$leaveImageAttachments->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('leaveImageAttachments.edit', [$leaveImageAttachments->id]) }}" class='btn btn-default btn-xs'>
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
