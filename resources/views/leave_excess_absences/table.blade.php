<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="leave-excess-absences-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Employeeid</th>
                <th>Leavedate</th>
                <th>Hoursabsent</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($leaveExcessAbsences as $leaveExcessAbsences)
                <tr>
                    <td>{{ $leaveExcessAbsences->id }}</td>
                    <td>{{ $leaveExcessAbsences->EmployeeId }}</td>
                    <td>{{ $leaveExcessAbsences->LeaveDate }}</td>
                    <td>{{ $leaveExcessAbsences->HoursAbsent }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['leaveExcessAbsences.destroy', $leaveExcessAbsences->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('leaveExcessAbsences.show', [$leaveExcessAbsences->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('leaveExcessAbsences.edit', [$leaveExcessAbsences->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $leaveExcessAbsences])
        </div>
    </div>
</div>
