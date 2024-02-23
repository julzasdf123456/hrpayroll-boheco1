<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="attached-accounts-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Accountnumber</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($attachedAccounts as $attachedAccounts)
                <tr>
                    <td>{{ $attachedAccounts->EmployeeId }}</td>
                    <td>{{ $attachedAccounts->AccountNumber }}</td>
                    <td>{{ $attachedAccounts->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['attachedAccounts.destroy', $attachedAccounts->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('attachedAccounts.show', [$attachedAccounts->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('attachedAccounts.edit', [$attachedAccounts->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $attachedAccounts])
        </div>
    </div>
</div>
