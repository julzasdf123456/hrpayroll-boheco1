<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="leave-conversions-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Vacationdays</th>
                <th>Sickdays</th>
                <th>Vacationamount</th>
                <th>Sickamount</th>
                <th>Year</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($leaveConversions as $leaveConversions)
                <tr>
                    <td>{{ $leaveConversions->EmployeeId }}</td>
                    <td>{{ $leaveConversions->VacationDays }}</td>
                    <td>{{ $leaveConversions->SickDays }}</td>
                    <td>{{ $leaveConversions->VacationAmount }}</td>
                    <td>{{ $leaveConversions->SickAmount }}</td>
                    <td>{{ $leaveConversions->Year }}</td>
                    <td>{{ $leaveConversions->Status }}</td>
                    <td>{{ $leaveConversions->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['leaveConversions.destroy', $leaveConversions->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('leaveConversions.show', [$leaveConversions->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('leaveConversions.edit', [$leaveConversions->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $leaveConversions])
        </div>
    </div>
</div>
