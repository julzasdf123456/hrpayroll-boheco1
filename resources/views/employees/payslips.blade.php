<table class="table table-hover">
    <thead>
        <th>Salary Period</th>
        <th>Date Covered</th>
        <th width="15%"></th>
    </thead>
    <tbody>
        @foreach ($payslips as $item)
            <tr>
                <td>{{ date('F d, Y', strtotime($item->SalaryPeriod)) }}</td>
                <td>{{ date('F d, Y', strtotime($item->DateFrom)) . '-' . date('F d, Y', strtotime($item->DateTo)) }}</td>
                <td class="text-right">
                    <a href="{{ route('payrollIndices.payslip', [$item->id]) }}"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>