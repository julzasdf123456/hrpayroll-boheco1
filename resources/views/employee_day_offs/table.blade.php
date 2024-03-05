<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="employee-day-offs-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Dayoff</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employeeDayOffs as $employeeDayOffs)
                <tr>
                    <td>{{ $employeeDayOffs->EmployeeId }}</td>
                    <td>{{ $employeeDayOffs->DayOff }}</td>
                    <td>{{ $employeeDayOffs->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['employeeDayOffs.destroy', $employeeDayOffs->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('employeeDayOffs.show', [$employeeDayOffs->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('employeeDayOffs.edit', [$employeeDayOffs->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $employeeDayOffs])
        </div>
    </div>
</div>
