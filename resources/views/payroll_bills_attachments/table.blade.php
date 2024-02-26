<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="payroll-bills-attachments-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Payrollid</th>
                <th>Billingmonth</th>
                <th>Amount</th>
                <th>Surcharges</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payrollBillsAttachments as $payrollBillsAttachments)
                <tr>
                    <td>{{ $payrollBillsAttachments->EmployeeId }}</td>
                    <td>{{ $payrollBillsAttachments->PayrollId }}</td>
                    <td>{{ $payrollBillsAttachments->BillingMonth }}</td>
                    <td>{{ $payrollBillsAttachments->Amount }}</td>
                    <td>{{ $payrollBillsAttachments->Surcharges }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['payrollBillsAttachments.destroy', $payrollBillsAttachments->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('payrollBillsAttachments.show', [$payrollBillsAttachments->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('payrollBillsAttachments.edit', [$payrollBillsAttachments->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $payrollBillsAttachments])
        </div>
    </div>
</div>
