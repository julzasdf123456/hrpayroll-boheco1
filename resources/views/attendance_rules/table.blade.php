<div class="table-responsive">
    <table class="table" id="attendanceRules-table">
        <thead>
            <tr>
                <th>Morningtimeinstart</th>
                <th>Morningtimeinend</th>
                <th>Morningtimeoutstart</th>
                <th>Morningtimeoutend</th>
                <th>Morning Absent Threshold</th>
                <th>Morning Undertime Threshold</th>
                <th>Afternoontimeinstart</th>
                <th>Afternoontimeinend</th>
                <th>Afternoontimeoutstart</th>
                <th>Afternoontimeoutend</th>
                <th>Afternoon Absent Threshold</th>
                <th>Afternoon Undertime Threshold</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($attendanceRules as $attendanceRules)
            <tr>
                <td>{{ $attendanceRules->MorningTimeInStart }}</td>
            <td>{{ $attendanceRules->MorningTimeInEnd }}</td>
            <td>{{ $attendanceRules->MorningTimeOutStart }}</td>
            <td>{{ $attendanceRules->MorningTimeOutEnd }}</td>
            <td>{{ $attendanceRules->MorningAbsentThreshold }}</td>
            <td>{{ $attendanceRules->MorningUndertimeThreshold }}</td>
            <td>{{ $attendanceRules->AfternoonTimeInStart }}</td>
            <td>{{ $attendanceRules->AfternoonTimeInEnd }}</td>
            <td>{{ $attendanceRules->AfternoonTimeOutStart }}</td>
            <td>{{ $attendanceRules->AfternoonTimeOutEnd }}</td>
            <td>{{ $attendanceRules->AfternoonAbsentThreshold }}</td>
            <td>{{ $attendanceRules->AfternoonUndertimeThreshold }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['attendanceRules.destroy', $attendanceRules->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('attendanceRules.show', [$attendanceRules->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('attendanceRules.edit', [$attendanceRules->id]) }}" class='btn btn-default btn-xs'>
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
