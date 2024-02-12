@php
    use App\Models\Employees;
@endphp

<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered" id="other-payroll-deductions-table">
            <thead>
            <tr>
                <th>Employee</th>
                <th>Deduction Name</th>
                <th>Deduction Details/Remarks</th>
                <th>Paryoll Deduction Schedule</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($otherPayrollDeductions as $otherPayrollDeduction)
                <tr>
                    <td>{{ Employees::getMergeName($otherPayrollDeduction) }}</td>
                    <td>{{ $otherPayrollDeduction->DeductionName }}</td>
                    <td>{{ $otherPayrollDeduction->DeductionDescription }}</td>
                    <td>{{ date('F d, Y', strtotime($otherPayrollDeduction->ScheduleDate)) }}</td>
                    <td class="text-right">{{ number_format($otherPayrollDeduction->Amount, 2) }}</td>
                    <td  style="width: 120px" class="text-right">
                        {!! Form::open(['route' => ['otherPayrollDeductions.destroy', $otherPayrollDeduction->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            {{-- <a href="{{ route('otherPayrollDeductions.show', [$otherPayrollDeduction->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a> --}}
                            {{-- <a href="{{ route('otherPayrollDeductions.edit', [$otherPayrollDeduction->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a> --}}
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
            @include('adminlte-templates::common.paginate', ['records' => $otherPayrollDeductions])
        </div>
    </div>
</div>
