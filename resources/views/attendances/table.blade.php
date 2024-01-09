<div class="table-responsive">
    <table class="table" id="attendances-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Morningtimein</th>
        <th>Morningtimeout</th>
        <th>Afternoontimein</th>
        <th>Afternoontimeout</th>
        <th>Ottimein</th>
        <th>Ottimeout</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($attendances as $attendances)
            <tr>
                <td>{{ $attendances->EmployeeId }}</td>
            <td>{{ $attendances->MorningTimeIn }}</td>
            <td>{{ $attendances->MorningTimeOut }}</td>
            <td>{{ $attendances->AfternoonTimeIn }}</td>
            <td>{{ $attendances->AfternoonTimeOut }}</td>
            <td>{{ $attendances->OTTimeIn }}</td>
            <td>{{ $attendances->OTTimeOut }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['attendances.destroy', $attendances->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('attendances.show', [$attendances->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('attendances.edit', [$attendances->id]) }}" class='btn btn-default btn-xs'>
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
