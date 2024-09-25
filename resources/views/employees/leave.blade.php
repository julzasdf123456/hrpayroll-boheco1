@php
    use App\Models\LeaveBalances;
@endphp

@if ($leaveBalance != null)
    <table class="table table-borderless table-sm" style="margin-top: 24px;">
        <tr>
            <td class="text-center">
                <span style="font-size: 1.5em; font-weight: bold">{{ LeaveBalances::toExpanded($leaveBalance->Vacation) }}</span>
            </td>
            <td class="text-center">
                <span style="font-size: 1.5em; font-weight: bold">{{ LeaveBalances::toExpanded($leaveBalance->Sick) }}</span>
            </td>
            <td class="text-center">
                <span style="font-size: 1.5em; font-weight: bold">{{ number_format($leaveBalance->Special, 1) }} days</span>
            </td>
            @if ($employees->Father === 'Yes') 
            <td class="text-center">
                <span style="font-size: 1.5em; font-weight: bold">{{ number_format($leaveBalance->Paternity, 1) }} days</span>
            </td>
            @endif
            @if ($employees->Mother === 'Yes') 
            <td class="text-center">
                <span style="font-size: 1.5em; font-weight: bold">{{ number_format($leaveBalance->Maternity, 1) }} days</span>
            </td>
            @endif
            @if ($employees->SoloMother === 'Yes')   
            <td class="text-center">
                <span style="font-size: 1.5em; font-weight: bold">{{ number_format($leaveBalance->MaternityForSoloMother, 1) }} days</span>
            </td>
            @endif
            @if ($employees->SoloParent === 'Yes') 
            <td class="text-center">
                <span style="font-size: 1.5em; font-weight: bold">{{ number_format($leaveBalance->SoloParent, 1) }} days</span>
            </td>
            @endif
        </tr>
        <tr>
            <td class="text-center text-muted">
                {{ number_format(LeaveBalances::toDay($leaveBalance->Vacation), 1) }}
                <br>
                Vacation
            </td>
            <td class="text-center text-muted">
                {{ number_format(LeaveBalances::toDay($leaveBalance->Sick), 1) }}
                <br>
                Sick
            </td>
            <td class="text-center text-muted">
                Special
            </td>
            @if ($employees->Father === 'Yes') 
            <td class="text-center text-muted">
                Paternity
            </td>
            @endif
            @if ($employees->Mother === 'Yes') 
            <td class="text-center text-muted">
                Maternity
            </td>
            @endif
            @if ($employees->SoloMother === 'Yes') 
            <td class="text-center text-muted">
                Maternity for <br> Solo Mother
            </td>
            @endif
            @if ($employees->SoloParent === 'Yes') 
            <td class="text-center text-muted">
                Solo Parent
            </td>
            @endif
        </tr>
    </table>

    <div style="margin-bottom: 18px;">
        <button class="btn btn-primary-skinny btn-sm float-right" data-toggle="modal" data-target="#modal-excess-leave" style="margin-bottom: 15px; margin-left: 5px;">Excess Leave Deductions</button>
        <button class="btn btn-primary-skinny btn-sm float-right" data-toggle="modal" data-target="#modal-leave-conversion-logs" style="margin-bottom: 15px; margin-left: 5px;">Leave Conversions</button>
        <button class="btn btn-primary-skinny btn-sm float-right" data-toggle="modal" data-target="#modal-leave-logs" style="margin-bottom: 15px;">Leave Credit Logs</button>
    </div>
@endif

<div style="padding-top: 56px;">
    <p class="text-muted">Leave Applications</p>
    <div id="app">
        <leave-list></leave-list>
    </div>
    @vite('resources/js/app.js')
</div>

@include('employees.modal_leave_balance_details')
@include('employees.modal_leave_conversion_logs')
@include('employees.modal_excess_leave')
