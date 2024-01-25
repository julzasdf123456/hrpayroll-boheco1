<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="overtime-signatories-table">
            <thead>
            <tr>
                <th>Overtimeid</th>
                <th>Employeeid</th>
                <th>Rank</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($overtimeSignatories as $overtimeSignatories)
                <tr>
                    <td>{{ $overtimeSignatories->OvertimeId }}</td>
                    <td>{{ $overtimeSignatories->EmployeeId }}</td>
                    <td>{{ $overtimeSignatories->Rank }}</td>
                    <td>{{ $overtimeSignatories->Status }}</td>
                    <td>{{ $overtimeSignatories->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['overtimeSignatories.destroy', $overtimeSignatories->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('overtimeSignatories.show', [$overtimeSignatories->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('overtimeSignatories.edit', [$overtimeSignatories->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $overtimeSignatories])
        </div>
    </div>
</div>
