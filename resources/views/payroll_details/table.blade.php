<div class="table-responsive">
    <table class="table" id="payrollDetails-table">
        <thead>
            <tr>
                <th>Payrolindexid</th>
        <th>Employeeid</th>
        <th>Grosssalary</th>
        <th>Totaldeductions</th>
        <th>Addons</th>
        <th>Vat</th>
        <th>Netsalary</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($payrollDetails as $payrollDetails)
            <tr>
                <td>{{ $payrollDetails->PayrolIndexId }}</td>
            <td>{{ $payrollDetails->EmployeeId }}</td>
            <td>{{ $payrollDetails->GrossSalary }}</td>
            <td>{{ $payrollDetails->TotalDeductions }}</td>
            <td>{{ $payrollDetails->AddOns }}</td>
            <td>{{ $payrollDetails->Vat }}</td>
            <td>{{ $payrollDetails->NetSalary }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['payrollDetails.destroy', $payrollDetails->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('payrollDetails.show', [$payrollDetails->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('payrollDetails.edit', [$payrollDetails->id]) }}" class='btn btn-default btn-xs'>
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
