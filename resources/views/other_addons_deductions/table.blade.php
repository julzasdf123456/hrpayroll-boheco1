<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="other-addons-deductions-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Deductionname</th>
                <th>Deductiondescription</th>
                <th>Scheduledate</th>
                <th>Notes</th>
                <th>Status</th>
                <th>Deductionamount</th>
                <th>Type</th>
                <th>Addonname</th>
                <th>Addonamount</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($otherAddonsDeductions as $otherAddonsDeductions)
                <tr>
                    <td>{{ $otherAddonsDeductions->EmployeeId }}</td>
                    <td>{{ $otherAddonsDeductions->DeductionName }}</td>
                    <td>{{ $otherAddonsDeductions->DeductionDescription }}</td>
                    <td>{{ $otherAddonsDeductions->ScheduleDate }}</td>
                    <td>{{ $otherAddonsDeductions->Notes }}</td>
                    <td>{{ $otherAddonsDeductions->Status }}</td>
                    <td>{{ $otherAddonsDeductions->DeductionAmount }}</td>
                    <td>{{ $otherAddonsDeductions->Type }}</td>
                    <td>{{ $otherAddonsDeductions->AddonName }}</td>
                    <td>{{ $otherAddonsDeductions->AddonAmount }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['otherAddonsDeductions.destroy', $otherAddonsDeductions->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('otherAddonsDeductions.show', [$otherAddonsDeductions->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('otherAddonsDeductions.edit', [$otherAddonsDeductions->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $otherAddonsDeductions])
        </div>
    </div>
</div>
