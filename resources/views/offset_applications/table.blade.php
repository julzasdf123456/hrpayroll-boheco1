<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="offset-applications-table">
            <thead>
            <tr>
                <th>Prepared By</th>
                <th>Date Prepared</th>
                <th>Employee Id</th>
                <th>Date Of Duty</th>
                <th>Time Start</th>
                <th>Time End</th>
                <th>Purpose of Duty</th>
                <th>Date Of Offset</th>
                <th>Offset Reason</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($offsetApplications as $offsetApplication)
                <tr>
                    <td>{{ $offsetApplication->PreparedBy }}</td>
                    <td>{{ $offsetApplication->DatePrepared }}</td>
                    <td>{{ $offsetApplication->EmployeeId }}</td>
                    <td>{{ $offsetApplication->DateOfDuty }}</td>
                    <td>{{ $offsetApplication->TimeStart }}</td>
                    <td>{{ $offsetApplication->TimeEnd }}</td>
                    <td>{{ $offsetApplication->PurposeOfDuty }}</td>
                    <td>{{ $offsetApplication->DateOfOffset }}</td>
                    <td>{{ $offsetApplication->OffsetReason }}</td>
                    <td>{{ $offsetApplication->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['offsetApplications.destroy', $offsetApplication->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('offsetApplications.show', [$offsetApplication->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('offsetApplications.edit', [$offsetApplication->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $offsetApplications])
        </div>
    </div>
</div>
