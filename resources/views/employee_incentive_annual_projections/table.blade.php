<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="employee-incentive-annual-projections-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Year</th>
                <th>Incentive</th>
                <th>Incentivedescription</th>
                <th>Amount</th>
                <th>Istaxable</th>
                <th>Maxuntaxableamount</th>
                <th>Deductmonthly</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employeeIncentiveAnnualProjections as $employeeIncentiveAnnualProjections)
                <tr>
                    <td>{{ $employeeIncentiveAnnualProjections->EmployeeId }}</td>
                    <td>{{ $employeeIncentiveAnnualProjections->Year }}</td>
                    <td>{{ $employeeIncentiveAnnualProjections->Incentive }}</td>
                    <td>{{ $employeeIncentiveAnnualProjections->IncentiveDescription }}</td>
                    <td>{{ $employeeIncentiveAnnualProjections->Amount }}</td>
                    <td>{{ $employeeIncentiveAnnualProjections->IsTaxable }}</td>
                    <td>{{ $employeeIncentiveAnnualProjections->MaxUntaxableAmount }}</td>
                    <td>{{ $employeeIncentiveAnnualProjections->DeductMonthly }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['employeeIncentiveAnnualProjections.destroy', $employeeIncentiveAnnualProjections->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('employeeIncentiveAnnualProjections.show', [$employeeIncentiveAnnualProjections->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('employeeIncentiveAnnualProjections.edit', [$employeeIncentiveAnnualProjections->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $employeeIncentiveAnnualProjections])
        </div>
    </div>
</div>
