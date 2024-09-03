<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="leave-balance-monthly-images-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Vacation</th>
                <th>Sick</th>
                <th>Special</th>
                <th>Maternity</th>
                <th>Maternityforsolomother</th>
                <th>Paternity</th>
                <th>Soloparent</th>
                <th>Notes</th>
                <th>Month</th>
                <th>Year</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($leaveBalanceMonthlyImages as $leaveBalanceMonthlyImage)
                <tr>
                    <td>{{ $leaveBalanceMonthlyImage->EmployeeId }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->Vacation }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->Sick }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->Special }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->Maternity }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->MaternityForSoloMother }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->Paternity }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->SoloParent }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->Notes }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->Month }}</td>
                    <td>{{ $leaveBalanceMonthlyImage->Year }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['leaveBalanceMonthlyImages.destroy', $leaveBalanceMonthlyImage->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('leaveBalanceMonthlyImages.show', [$leaveBalanceMonthlyImage->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('leaveBalanceMonthlyImages.edit', [$leaveBalanceMonthlyImage->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $leaveBalanceMonthlyImages])
        </div>
    </div>
</div>
