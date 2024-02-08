<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="employee-bonuses-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Incentive</th>
                <th>Incentivedescription</th>
                <th>Baseamount</th>
                <th>Maxuntaxableamount</th>
                <th>Deductuponreceipt</th>
                <th>Taxdeductionamount</th>
                <th>Netamountpay</th>
                <th>Datereleased</th>
                <th>Notes</th>
                <th>Year</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employeeBonuses as $employeeBonuses)
                <tr>
                    <td>{{ $employeeBonuses->EmployeeId }}</td>
                    <td>{{ $employeeBonuses->Incentive }}</td>
                    <td>{{ $employeeBonuses->IncentiveDescription }}</td>
                    <td>{{ $employeeBonuses->BaseAmount }}</td>
                    <td>{{ $employeeBonuses->MaxUntaxableAmount }}</td>
                    <td>{{ $employeeBonuses->DeductUponReceipt }}</td>
                    <td>{{ $employeeBonuses->TaxDeductionAmount }}</td>
                    <td>{{ $employeeBonuses->NetAmountPay }}</td>
                    <td>{{ $employeeBonuses->DateReleased }}</td>
                    <td>{{ $employeeBonuses->Notes }}</td>
                    <td>{{ $employeeBonuses->Year }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['employeeBonuses.destroy', $employeeBonuses->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('employeeBonuses.show', [$employeeBonuses->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('employeeBonuses.edit', [$employeeBonuses->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $employeeBonuses])
        </div>
    </div>
</div>
