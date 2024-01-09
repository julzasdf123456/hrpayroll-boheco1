<div class="table-responsive">
    <table class="table" id="payrollIndices-table">
        <thead>
            <tr>
                <th>Datefrom</th>
        <th>Dateto</th>
        <th>Employeetype</th>
        <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($payrollIndices as $payrollIndex)
            <tr>
                <td>{{ $payrollIndex->DateFrom }}</td>
            <td>{{ $payrollIndex->DateTo }}</td>
            <td>{{ $payrollIndex->EmployeeType }}</td>
            <td>{{ $payrollIndex->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['payrollIndices.destroy', $payrollIndex->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('payrollIndices.show', [$payrollIndex->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('payrollIndices.edit', [$payrollIndex->id]) }}" class='btn btn-default btn-xs'>
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
