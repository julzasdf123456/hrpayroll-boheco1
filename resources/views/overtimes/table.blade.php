<div class="table-responsive">
    <table class="table" id="overtimes-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Dateofot</th>
        <th>From</th>
        <th>To</th>
        <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($overtimes as $overtimes)
            <tr>
                <td>{{ $overtimes->EmployeeId }}</td>
            <td>{{ $overtimes->DateOfOT }}</td>
            <td>{{ $overtimes->From }}</td>
            <td>{{ $overtimes->To }}</td>
            <td>{{ $overtimes->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['overtimes.destroy', $overtimes->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('overtimes.show', [$overtimes->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('overtimes.edit', [$overtimes->id]) }}" class='btn btn-default btn-xs'>
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
