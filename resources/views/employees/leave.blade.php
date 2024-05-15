@php
    use App\Models\LeaveBalances;
@endphp

@if ($leaveBalance != null)
    <table class="table table-borderless table-sm" style="margin-top: 24px;">
        <tr>
            <td class="text-center">
                <span style="font-size: 3em; font-weight: bold">{{ number_format(LeaveBalances::toDay($leaveBalance->Vacation), 1) }}</span>
            </td>
            <td class="text-center">
                <span style="font-size: 3em; font-weight: bold">{{ number_format(LeaveBalances::toDay($leaveBalance->Sick), 1) }}</span>
            </td>
            <td class="text-center">
                <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->Special, 1) }}</span>
            </td>
            @if ($employees->Gender == 'Male')
                <td class="text-center">
                    <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->Paternity, 1) }}</span>
                </td>
            @else
                <td class="text-center">
                    <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->Maternity, 1) }}</span>
                </td>       
                <td class="text-center">
                    <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->MaternityForSoloMother, 1) }}</span>
                </td>
            @endif     
            
            <td class="text-center">
                <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->SoloParent, 1) }}</span>
            </td>
        </tr>
        <tr>
            <td class="text-center text-muted">
                {{ LeaveBalances::toExpanded($leaveBalance->Vacation) }}
                <br>
                Vacation
            </td>
            <td class="text-center text-muted">
                {{ LeaveBalances::toExpanded($leaveBalance->Sick) }}
                <br>
                Sick
            </td>
            <td class="text-center text-muted">
                Special
            </td>
            @if ($employees->Gender == 'Male')
                <td class="text-center text-muted">
                    Paternity
                </td>
            @else
                <td class="text-center text-muted">
                    Maternity
                </td>
                <td class="text-center text-muted">
                    Maternity for <br> Solo Mother
                </td>
            @endif
            <td class="text-center text-muted">
                Solo Parent
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 18px;">
        <button class="btn btn-primary-skinny btn-sm float-right" data-toggle="modal" data-target="#modal-excess-leave" style="margin-bottom: 15px; margin-left: 5px;">Excess Leave Deductions</button>
        <button class="btn btn-primary-skinny btn-sm float-right" data-toggle="modal" data-target="#modal-leave-conversion-logs" style="margin-bottom: 15px; margin-left: 5px;">Leave Conversions</button>
        <button class="btn btn-primary-skinny btn-sm float-right" data-toggle="modal" data-target="#modal-leave-logs" style="margin-bottom: 15px;">Leave Credit Logs</button>
    </div>
@endif

<div style="padding-top: 56px;">
    <p class="text-muted">Latest Leave Applications</p>
    @if ($leaveApplications != null)
        @if (count($leaveApplications) < 1)
            <p class="text-center"><i>No leave application recorded</i></p>
        @else
            <table class="table table-hover table-borderless table-sm">
                <thead>
                    <th>Date Filed</th>
                    <th>Content</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($leaveApplications as $item)
                        <tr>
                            <td>
                                @if ($item->Status != 'APPROVED')
                                    <i class="fas fa-info-circle text-warning"></i>
                                @else
                                    <i class="fas fa-check-circle text-success"></i>
                                @endif
                                {{ date('F d, Y', strtotime($item->created_at)) }}
                            </td>
                            <td>
                                <p class="ellipsize-dynamic">{{ $item->Content }}</p>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('leaveApplications.show', [$item->id]) }}" class="btn btn-primary-skinny btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <p class="text-center"><i>No leave application recorded</i></p>
    @endif
</div>

@include('employees.modal_leave_balance_details')
@include('employees.modal_leave_conversion_logs')
@include('employees.modal_excess_leave')
