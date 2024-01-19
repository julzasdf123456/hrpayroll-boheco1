<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="attendane-confirmation-signatories-table">
            <thead>
            <tr>
                <th>Attendanceconfirmationid</th>
                <th>Employeeid</th>
                <th>Rank</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($attendaneConfirmationSignatories as $attendaneConfirmationSignatories)
                <tr>
                    <td>{{ $attendaneConfirmationSignatories->AttendanceConfirmationId }}</td>
                    <td>{{ $attendaneConfirmationSignatories->EmployeeId }}</td>
                    <td>{{ $attendaneConfirmationSignatories->Rank }}</td>
                    <td>{{ $attendaneConfirmationSignatories->Status }}</td>
                    <td>{{ $attendaneConfirmationSignatories->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['attendaneConfirmationSignatories.destroy', $attendaneConfirmationSignatories->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('attendaneConfirmationSignatories.show', [$attendaneConfirmationSignatories->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('attendaneConfirmationSignatories.edit', [$attendaneConfirmationSignatories->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $attendaneConfirmationSignatories])
        </div>
    </div>
</div>
