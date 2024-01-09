<div class="table-responsive">
    <table class="table" id="leaveApplications-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Datefrom</th>
        <th>Dateto</th>
        <th>Numberofdays</th>
        <th>Content</th>
        <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveApplications as $leaveApplications)
            <tr>
                <td>{{ $leaveApplications->EmployeeId }}</td>
            <td>{{ $leaveApplications->DateFrom }}</td>
            <td>{{ $leaveApplications->DateTo }}</td>
            <td>{{ $leaveApplications->NumberOfDays }}</td>
            <td>{{ $leaveApplications->Content }}</td>
            <td>{{ $leaveApplications->Status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveApplications.destroy', $leaveApplications->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveApplications.show', [$leaveApplications->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('leaveApplications.edit', [$leaveApplications->id]) }}" class='btn btn-default btn-xs'>
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
