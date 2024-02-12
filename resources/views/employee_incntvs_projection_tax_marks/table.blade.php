<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="employee-incntvs-projection-tax-marks-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Incentive</th>
                <th>Salaryperiod</th>
                <th>Deducted</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employeeIncntvsProjectionTaxMarks as $employeeIncntvsProjectionTaxMark)
                <tr>
                    <td>{{ $employeeIncntvsProjectionTaxMark->EmployeeId }}</td>
                    <td>{{ $employeeIncntvsProjectionTaxMark->Incentive }}</td>
                    <td>{{ $employeeIncntvsProjectionTaxMark->SalaryPeriod }}</td>
                    <td>{{ $employeeIncntvsProjectionTaxMark->Deducted }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['employeeIncntvsProjectionTaxMarks.destroy', $employeeIncntvsProjectionTaxMark->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('employeeIncntvsProjectionTaxMarks.show', [$employeeIncntvsProjectionTaxMark->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('employeeIncntvsProjectionTaxMarks.edit', [$employeeIncntvsProjectionTaxMark->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $employeeIncntvsProjectionTaxMarks])
        </div>
    </div>
</div>
