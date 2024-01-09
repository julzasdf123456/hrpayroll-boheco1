<div class="table-responsive">
    <table class="table" id="payrollSchedules-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Starttime</th>
        <th>Breakstart</th>
        <th>Breakend</th>
        <th>Endtime</th>
        <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($payrollSchedules as $payrollSchedules)
            <tr>
                <td>{{ $payrollSchedules->Name }}</td>
            <td>{{ $payrollSchedules->StartTime }}</td>
            <td>{{ $payrollSchedules->BreakStart }}</td>
            <td>{{ $payrollSchedules->BreakEnd }}</td>
            <td>{{ $payrollSchedules->EndTime }}</td>
            <td>{{ $payrollSchedules->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['payrollSchedules.destroy', $payrollSchedules->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('payrollSchedules.show', [$payrollSchedules->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('payrollSchedules.edit', [$payrollSchedules->id]) }}" class='btn btn-default btn-xs'>
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
