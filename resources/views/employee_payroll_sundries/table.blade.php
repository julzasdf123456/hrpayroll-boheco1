<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="employee-payroll-sundries-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Longevity</th>
                <th>Riceallowance</th>
                <th>Insurances</th>
                <th>Pagibigcontribution</th>
                <th>Pagibigloan</th>
                <th>Ssscontribution</th>
                <th>Sssloan</th>
                <th>Philhealth</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employeePayrollSundries as $employeePayrollSundries)
                <tr>
                    <td>{{ $employeePayrollSundries->EmployeeId }}</td>
                    <td>{{ $employeePayrollSundries->Longevity }}</td>
                    <td>{{ $employeePayrollSundries->RiceAllowance }}</td>
                    <td>{{ $employeePayrollSundries->Insurances }}</td>
                    <td>{{ $employeePayrollSundries->PagIbigContribution }}</td>
                    <td>{{ $employeePayrollSundries->PagIbigLoan }}</td>
                    <td>{{ $employeePayrollSundries->SSSContribution }}</td>
                    <td>{{ $employeePayrollSundries->SSSLoan }}</td>
                    <td>{{ $employeePayrollSundries->PhilHealth }}</td>
                    <td>{{ $employeePayrollSundries->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['employeePayrollSundries.destroy', $employeePayrollSundries->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('employeePayrollSundries.show', [$employeePayrollSundries->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('employeePayrollSundries.edit', [$employeePayrollSundries->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $employeePayrollSundries])
        </div>
    </div>
</div>
