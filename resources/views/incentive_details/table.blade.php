<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="incentive-details-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Employeeid</th>
                <th>Incentivesid</th>
                <th>Subtotal</th>
                <th>Basicsalary</th>
                <th>Termwage</th>
                <th>Otherdeductions</th>
                <th>Bempc</th>
                <th>Netpay</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($incentiveDetails as $incentiveDetails)
                <tr>
                    <td>{{ $incentiveDetails->id }}</td>
                    <td>{{ $incentiveDetails->EmployeeId }}</td>
                    <td>{{ $incentiveDetails->IncentivesId }}</td>
                    <td>{{ $incentiveDetails->SubTotal }}</td>
                    <td>{{ $incentiveDetails->BasicSalary }}</td>
                    <td>{{ $incentiveDetails->TermWage }}</td>
                    <td>{{ $incentiveDetails->OtherDeductions }}</td>
                    <td>{{ $incentiveDetails->BEMPC }}</td>
                    <td>{{ $incentiveDetails->NetPay }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['incentiveDetails.destroy', $incentiveDetails->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('incentiveDetails.show', [$incentiveDetails->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('incentiveDetails.edit', [$incentiveDetails->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $incentiveDetails])
        </div>
    </div>
</div>
