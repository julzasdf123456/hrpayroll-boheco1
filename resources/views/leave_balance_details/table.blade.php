<div class="table-responsive">
    <table class="table" id="leaveBalanceDetails-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Method</th>
        <th>Days</th>
        <th>Details</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveBalanceDetails as $leaveBalanceDetails)
            <tr>
                <td>{{ $leaveBalanceDetails->EmployeeId }}</td>
            <td>{{ $leaveBalanceDetails->Method }}</td>
            <td>{{ $leaveBalanceDetails->Days }}</td>
            <td>{{ $leaveBalanceDetails->Details }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveBalanceDetails.destroy', $leaveBalanceDetails->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveBalanceDetails.show', [$leaveBalanceDetails->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('leaveBalanceDetails.edit', [$leaveBalanceDetails->id]) }}" class='btn btn-default btn-xs'>
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
