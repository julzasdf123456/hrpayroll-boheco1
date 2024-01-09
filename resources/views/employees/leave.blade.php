@if ($leaveBalance != null)
    <table class="table table-borderless table-sm">
        <tr>
            <td class="text-center text-primary">
                <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->Vacation, 1) }}</span>
            </td>
            <td class="text-center text-success">
                <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->Sick, 1) }}</span>
            </td>
            <td class="text-center text-danger">
                <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->Special, 1) }}</span>
            </td>
            @if ($employees->Gender == 'Male')
                <td class="text-center text-info">
                    <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->Paternity, 1) }}</span>
                </td>
            @else
                <td class="text-center text-info">
                    <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->Maternity, 1) }}</span>
                </td>       
                <td class="text-center text-info">
                    <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->MaternityForSoloMother, 1) }}</span>
                </td>
            @endif     
            
            <td class="text-center text-info">
                <span style="font-size: 3em; font-weight: bold">{{ number_format($leaveBalance->SoloParent, 1) }}</span>
            </td>
        </tr>
        <tr>
            <td class="text-center text-primary">
                Vacation
            </td>
            <td class="text-center text-success">
                Sick
            </td>
            <td class="text-center text-danger">
                Special
            </td>
            @if ($employees->Gender == 'Male')
                <td class="text-center text-info">
                    Paternity
                </td>
            @else
                <td class="text-center text-info">
                    Maternity
                </td>
                <td class="text-center text-info">
                    Maternity for <br> Solo Mother
                </td>
            @endif
            <td class="text-center text-info">
                Solo Parent
            </td>
        </tr>
    </table>

    <button class="btn btn-xs btn-primary float-right" data-toggle="modal" data-target="#modal-leave-logs" style="margin-bottom: 15px;">View Logs</button>
@endif

<span class="text-muted"><i>All Leave Applications Filed</i></span>
@if ($leaveApplications != null)
    @if (count($leaveApplications) < 1)
        <p class="text-center"><i>No leave application recorded</i></p>
    @else
        <table class="table table-hover table-sm">
            <thead>
                <th width="20%;">Date Filed</th>
                <th width="50%;">Content</th>
                <th width="5%"></th>
            </thead>
            <tbody>
                @foreach ($leaveApplications as $item)
                    <tr>
                        <td><a href="{{ route('leaveApplications.show', [$item->id]) }}">{{ date('F d, Y', strtotime($item->created_at)) }}</a></td>
                        <td><p class="ellipsize-dynamic">{{ $item->Content }}</p></td>
                        <td>
                            @if ($item->Status != 'APPROVED')
                                <i class="fas fa-info-circle text-warning"></i>
                            @else
                                <i class="fas fa-check-circle text-success"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@else
    <p class="text-center"><i>No leave application recorded</i></p>
@endif

@include('employees.modal_leave_balance_details')
