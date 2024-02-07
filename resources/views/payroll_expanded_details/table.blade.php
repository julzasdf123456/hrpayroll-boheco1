<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="payroll-expanded-details-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Employeeid</th>
                <th>Salaryperiod</th>
                <th>From</th>
                <th>To</th>
                <th>Totalhoursrendered</th>
                <th>Totalworkedhours</th>
                <th>Monthlywage</th>
                <th>Termwage</th>
                <th>Overtimehours</th>
                <th>Overtimeamount</th>
                <th>Absenthours</th>
                <th>Absentamount</th>
                <th>Longevity</th>
                <th>Ricelaundry</th>
                <th>Othersalaryadditions</th>
                <th>Othersalarydeductions</th>
                <th>Totalpartialamount</th>
                <th>Motorycleloan</th>
                <th>Pagibigcontribution</th>
                <th>Pagibigloan</th>
                <th>Ssscontribution</th>
                <th>Sssloan</th>
                <th>Philhealthcontribution</th>
                <th>Otherdeductions</th>
                <th>Salarywithholdingtax</th>
                <th>Totalwithholdingtax</th>
                <th>Totaldeductions</th>
                <th>Netpay</th>
                <th>Generatedby</th>
                <th>Auditedby</th>
                <th>Checkedby</th>
                <th>Approvedby</th>
                <th>Generateddate</th>
                <th>Auditeddate</th>
                <th>Checkeddate</th>
                <th>Approveddate</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payrollExpandedDetails as $payrollExpandedDetails)
                <tr>
                    <td>{{ $payrollExpandedDetails->id }}</td>
                    <td>{{ $payrollExpandedDetails->EmployeeId }}</td>
                    <td>{{ $payrollExpandedDetails->SalaryPeriod }}</td>
                    <td>{{ $payrollExpandedDetails->From }}</td>
                    <td>{{ $payrollExpandedDetails->To }}</td>
                    <td>{{ $payrollExpandedDetails->TotalHoursRendered }}</td>
                    <td>{{ $payrollExpandedDetails->TotalWorkedHours }}</td>
                    <td>{{ $payrollExpandedDetails->MonthlyWage }}</td>
                    <td>{{ $payrollExpandedDetails->TermWage }}</td>
                    <td>{{ $payrollExpandedDetails->OvertimeHours }}</td>
                    <td>{{ $payrollExpandedDetails->OvertimeAmount }}</td>
                    <td>{{ $payrollExpandedDetails->AbsentHours }}</td>
                    <td>{{ $payrollExpandedDetails->AbsentAmount }}</td>
                    <td>{{ $payrollExpandedDetails->Longevity }}</td>
                    <td>{{ $payrollExpandedDetails->RiceLaundry }}</td>
                    <td>{{ $payrollExpandedDetails->OtherSalaryAdditions }}</td>
                    <td>{{ $payrollExpandedDetails->OtherSalaryDeductions }}</td>
                    <td>{{ $payrollExpandedDetails->TotalPartialAmount }}</td>
                    <td>{{ $payrollExpandedDetails->MotorycleLoan }}</td>
                    <td>{{ $payrollExpandedDetails->PagIbigContribution }}</td>
                    <td>{{ $payrollExpandedDetails->PagIbigLoan }}</td>
                    <td>{{ $payrollExpandedDetails->SSSContribution }}</td>
                    <td>{{ $payrollExpandedDetails->SSSLoan }}</td>
                    <td>{{ $payrollExpandedDetails->PhilHealthContribution }}</td>
                    <td>{{ $payrollExpandedDetails->OtherDeductions }}</td>
                    <td>{{ $payrollExpandedDetails->SalaryWithholdingTax }}</td>
                    <td>{{ $payrollExpandedDetails->TotalWithholdingTax }}</td>
                    <td>{{ $payrollExpandedDetails->TotalDeductions }}</td>
                    <td>{{ $payrollExpandedDetails->NetPay }}</td>
                    <td>{{ $payrollExpandedDetails->GeneratedBy }}</td>
                    <td>{{ $payrollExpandedDetails->AuditedBy }}</td>
                    <td>{{ $payrollExpandedDetails->CheckedBy }}</td>
                    <td>{{ $payrollExpandedDetails->ApprovedBy }}</td>
                    <td>{{ $payrollExpandedDetails->GeneratedDate }}</td>
                    <td>{{ $payrollExpandedDetails->AuditedDate }}</td>
                    <td>{{ $payrollExpandedDetails->CheckedDate }}</td>
                    <td>{{ $payrollExpandedDetails->ApprovedDate }}</td>
                    <td>{{ $payrollExpandedDetails->Status }}</td>
                    <td>{{ $payrollExpandedDetails->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['payrollExpandedDetails.destroy', $payrollExpandedDetails->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('payrollExpandedDetails.show', [$payrollExpandedDetails->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('payrollExpandedDetails.edit', [$payrollExpandedDetails->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $payrollExpandedDetails])
        </div>
    </div>
</div>
