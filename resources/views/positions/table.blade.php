<div class="table-responsive">
    <table class="table" id="positions-table">
        <thead>
            <tr>
                <th>Position</th>
        <th>Description</th>
        <th>Level</th>
        <th>Parentpositionid</th>
        <th>Department</th>
        <th>Basic Salary</th>
            <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($positions as $positions)
            <tr>
                <td>{{ $positions->Position }}</td>
            <td>{{ $positions->Description }}</td>
            <td>{{ $positions->Level }}</td>
            <td>{{ $positions->ParentPositionId }}</td>
            <td>{{ $positions->Department }}</td>
            <td>{{ number_format($positions->BasicSalary, 2) }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['positions.destroy', $positions->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('positions.show', [$positions->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('positions.edit', [$positions->id]) }}" class='btn btn-default btn-xs'>
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
