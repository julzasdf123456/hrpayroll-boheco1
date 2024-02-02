<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="loans-table">
            <thead>
            <tr>
                <th>Loanfor</th>
                <th>Loanname</th>
                <th>Loandescription</th>
                <th>Interestrate</th>
                <th>Loanamount</th>
                <th>Terms</th>
                <th>Termunit</th>
                <th>Employeeid</th>
                <th>Paymentterm</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($loans as $loans)
                <tr>
                    <td>{{ $loans->LoanFor }}</td>
                    <td>{{ $loans->LoanName }}</td>
                    <td>{{ $loans->LoanDescription }}</td>
                    <td>{{ $loans->InterestRate }}</td>
                    <td>{{ $loans->LoanAmount }}</td>
                    <td>{{ $loans->Terms }}</td>
                    <td>{{ $loans->TermUnit }}</td>
                    <td>{{ $loans->EmployeeId }}</td>
                    <td>{{ $loans->PaymentTerm }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['loans.destroy', $loans->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('loans.show', [$loans->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('loans.edit', [$loans->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $loans])
        </div>
    </div>
</div>
