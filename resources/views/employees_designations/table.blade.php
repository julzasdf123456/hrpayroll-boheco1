<div class="table-responsive">
    <table class="table" id="employeesDesignations-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Designation</th>
        <th>Description</th>
        <th>Datestarted</th>
        <th>Dateend</th>
        <th>Salarygrade</th>
        <th>Salaryamount</th>
        <th>Salaryaddons</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($employeesDesignations as $employeesDesignations)
            <tr>
                <td>{{ $employeesDesignations->EmployeeId }}</td>
            <td>{{ $employeesDesignations->Designation }}</td>
            <td>{{ $employeesDesignations->Description }}</td>
            <td>{{ $employeesDesignations->DateStarted }}</td>
            <td>{{ $employeesDesignations->DateEnd }}</td>
            <td>{{ $employeesDesignations->SalaryGrade }}</td>
            <td>{{ $employeesDesignations->SalaryAmount }}</td>
            <td>{{ $employeesDesignations->SalaryAddOns }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['employeesDesignations.destroy', $employeesDesignations->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('employeesDesignations.show', [$employeesDesignations->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('employeesDesignations.edit', [$employeesDesignations->id]) }}" class='btn btn-default btn-xs'>
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
