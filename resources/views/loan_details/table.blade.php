<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="loan-details-table">
            <thead>
            <tr>
                <th>Loanid</th>
                <th>Interest</th>
                <th>Principal</th>
                <th>Monthlyammortization</th>
                <th>Month</th>
                <th>Paid</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($loanDetails as $loanDetails)
                <tr>
                    <td>{{ $loanDetails->LoanId }}</td>
                    <td>{{ $loanDetails->Interest }}</td>
                    <td>{{ $loanDetails->Principal }}</td>
                    <td>{{ $loanDetails->MonthlyAmmortization }}</td>
                    <td>{{ $loanDetails->Month }}</td>
                    <td>{{ $loanDetails->Paid }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['loanDetails.destroy', $loanDetails->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('loanDetails.show', [$loanDetails->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('loanDetails.edit', [$loanDetails->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $loanDetails])
        </div>
    </div>
</div>
