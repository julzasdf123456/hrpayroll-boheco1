<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="offset-applications-table">
            <thead>
            <tr>
                <th>Preparedby</th>
                <th>Dateprepared</th>
                <th>Employeeid</th>
                <th>Dateofduty</th>
                <th>Timestart</th>
                <th>Timeend</th>
                <th>Purposeofduty</th>
                <th>Dateofoffset</th>
                <th>Offsetreason</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($offsetApplications as $offsetApplications)
                <tr>
                    <td>{{ $offsetApplications->PreparedBy }}</td>
                    <td>{{ $offsetApplications->DatePrepared }}</td>
                    <td>{{ $offsetApplications->EmployeeId }}</td>
                    <td>{{ $offsetApplications->DateOfDuty }}</td>
                    <td>{{ $offsetApplications->TimeStart }}</td>
                    <td>{{ $offsetApplications->TimeEnd }}</td>
                    <td>{{ $offsetApplications->PurposeOfDuty }}</td>
                    <td>{{ $offsetApplications->DateOfOffset }}</td>
                    <td>{{ $offsetApplications->OffsetReason }}</td>
                    <td>{{ $offsetApplications->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['offsetApplications.destroy', $offsetApplications->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('offsetApplications.show', [$offsetApplications->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('offsetApplications.edit', [$offsetApplications->id]) }}"
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
