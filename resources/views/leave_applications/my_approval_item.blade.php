@php
    use App\Models\Employees;
    use Carbon\Carbon;
    use App\Models\LeaveDays;
    use App\Models\LeaveBalances;

    $leavingDays = 0;
    if (!function_exists('leaveDaysTotal')) {
        function leaveDaysTotal($leave): float {
        if ($leave->LeaveType == "Vacation"){
            return $leave->Vacation / 60 / 8;
        } else if ( $leave->LeaveType == "Sick") {
            return $leave->Sick / 60 / 8;
        } else if ( $leave->LeaveType == "Special") {
            return $leave->Special;
        } else if ( $leave->LeaveType == "Maternity") {
            return $leave->Maternity;
        } else if ( $leave->LeaveType == "Paternity") {
            return $leave->Paternity;
        } else if ( $leave->LeaveType == "SoloParent") {
            return $leave->SoloParent;
        } else {
            return $leave->MaternityForSoloMother;
        }
    }
    }
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>My Leave Approvals</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        @php
            $leaveDays = LeaveDays::where('LeaveId', $leave->id)->orderBy('LeaveDate')->get();
        @endphp
        <div id="card-{{ $leave->id }}" class="col">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title" style="width:40%;">
                        <h4 class="no-pads"><strong>{{ Employees::getMergeName($leave) }}</strong></h4>
                        <span class="badge bg-info m-auto">{{ $leave->LeaveType }}</span>
                    </span>
                </div>
                <div class="d-flex mx-3" style="color:darkkhaki;width:400px;">
                    @if ($leave->MarkAsAbsent)
                        <i class="fas fa-info-circle m-auto"></i>
                        <p class="my-auto">This leave has filed as unpaid by this employee.</p>
                    @endif
                </div>
                <div class="card-body">
                    <p style="margin: 0; padding: 0; font-size: 1.3em;">{{ $leave->Content }}</p>
                    <div class="divider"></div>
                    <span class="text-muted float-left">Leave Date(s):</span>
                    <br>
                    <ul>
                        @foreach ($leaveDays as $days)
                            <li><strong>{{ Carbon::parse(str_replace(':AM', ' AM', str_replace(':PM', ' PM', $days->LeaveDate)))->format('M d, Y') }}</strong>
                                <span class="text-muted">({{ $days->Duration }})</span>
                            </li>
                            @php
                                if ($days->Duration == "WHOLE") {
                                    $leavingDays += 1;
                                } else {
                                    $leavingDays += 0.5;
                                }
                            @endphp
                        @endforeach
                    </ul>
                    <span class="text-muted">No. of Days: <strong>{{ $leavingDays }}</strong></span>
                    <br>
                    <span class="text-muted">Date Filed:
                        <strong>{{ date('D, M d, Y', strtotime($leave->created_at)) }}</strong></span>
                    <br><br>

                    @if ($leave->LeaveType == 'Sick')
                        <p class="text-muted">Document Attachment(s)</p>
                        <div class="row" id="imgs-data">
                            @if (count($leaveImgs) < 1)
                                <ul>
                                    <li>No attachments posted by the employee.</li>
                                </ul>
                            @else
                                @foreach ($leaveImgs as $item)
                                    <div class="col-md-3" id="{{ $item->id }}">
                                        {{-- <button class="btn btn-xs btn-danger" style="position: absolute; right: 10px; top: 5px;" onclick="removeImg('{{ $item->id }}')"><i class="fas fa-trash"></i></button> --}}
                                        <img src="{{ $item->HexImage }}" style="width: 100%;" alt="">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
                @if ($leavingDays > leaveDaysTotal($leave))
                    <div class="mx-4 mt-2" style="color:#ffbe33;">
                        <strong>WARNING: </strong>
                        <p>This employee has insufficent leave credits. The rest of the later leave dates will be marked as unpaid if you wish to proceed approving this leave.</p>
                    </div>
                @endif
                
                
                <div class="card-footer">
                    {{-- @if ($leavingDays > leaveDaysTotal($leave))
                        <span style="color:gray;">INSUFFICIENT BALANCE CANNOT APPROVE.</span>
                    @else
                        <button id="approveBtn" class="btn btn-sm btn-success"
                        onclick="approveLeave(`{{ $leave->id }}`)" sig-id="{{ $leave->SignatoryId }}">
                        <i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                    @endif --}}

                    @if(count($leaveLowerSignatories) > 0) 
                        <span style="color:gray;">Kindly wait for the other signatories to confirm.</span>
                    @elseif ($leave->Status !== 'APPROVED' || $leaveTopSignatory->Status !== 'APPROVED')
                        <button id="approveBtn" class="btn btn-sm btn-success"
                        onclick="approveLeave(`{{ $leave->id }}`)" sig-id="{{ $leave->SignatoryId }}">
                        <i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                        
                        <button onclick="rejectLeave(`{{ $leave->id }}`, `{{ $leave->SignatoryId }}`)"
                            class="btn btn-sm btn-danger float-right">
                            <i class="fas fa-times-circle ico-tab-mini"></i>Reject</button>
                    @else
                            <span style="color:gray;">ALREADY CONFIRMED AND CANNOT BE CONFIRMED AGAIN.</span>
                    @endif
                    
                    
                </div>
            </div>
        </div>
        @if (count($leaveSignatories) > 0)
            <div class="col-lg-4 card p-3 d-flex flex-col">
                <p class="text-muted">Signatory Logs</p>
                @foreach ($leaveSignatories as $item)
                    <div
                        class="mb-2 p-2 {{ Auth::user()->ColorProfile != null ? 'border-left-dark' : 'border-left-light' }}">
                        @if ($item->Status === 'APPROVED')
                            <span class="badge bg-success">{{ $item->Status }}</span>
                        @elseif ($item->Status === 'REJECTED')
                            <span class="badge bg-danger">{{ $item->Status }}</span>
                        @else
                            <span
                                class="badge {{ Auth::user()->ColorProfile != null ? 'bg-white' : 'bg-dark' }}">{{ $item->Status == null || $item->Status == 'Trashed' || $item->Status == 'Filed' ? 'PENDING' : $item->Status }}</span>
                        @endif

                        <span style="font-size: .85em;"
                            class="text-muted float-right">{{ date('M d, Y', strtotime($item->updated_at)) }}</span>
                        <br>
                        <p class="no-pads"><strong>{{ Employees::getMergeName($item) }} {{ $item->EmployeeId == Auth::id()? "(You)" :"" }}</strong></p>
                        @if ($item->Notes != null)
                            <span class="text-muted">{{ $item->Notes }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- LEAVE BALANCES HERE --}}
        <div class="col-lg-5">
            {{-- LEAVA BALANCES --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Leave Balance</span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            @if ($leave->LeaveType == 'Vacation')
                                <tr>
                                    <td>VACATION</td>
                                    <td class="text-right">{{ LeaveBalances::toExpanded($leave->Vacation)}}</td>
                                </tr>
                            @elseif ($leave->LeaveType == 'Sick')
                            <tr>
                                <td>SICK</td>
                                <td class="text-right">{{ LeaveBalances::toExpanded($leave->Sick) }}</td>
                            </tr>
                            @elseif ($leave->LeaveType == 'Special')
                            <tr>
                                <td>SPECIAL</td>
                                <td class="text-right">{{ $leave->Special }} Days</td>
                            </tr>
                            @elseif ($leave->LeaveType == 'Maternity')
                            <tr>
                                <td>MATERNITY</td>
                                <td class="text-right">{{ $leave->Maternity }} Days</td>
                            </tr>
                            @elseif ($leave->LeaveType == 'Paternity')
                            <tr>
                                <td>PATERNITY</td>
                                <td class="text-right">{{ $leave->Paternity }} Days</td>
                            </tr>
                            @elseif ($leave->LeaveType == 'SoloParent')
                            <tr>
                                <td>SOLO PARENT</td>
                                <td class="text-right">{{ $leave->SoloParent }} Days</td>
                            </tr>
                            @else
                            <tr>
                                <td>MATERNITY (SOLO PARENT)</td>
                                <td class="text-right">{{ $leave->MaternityForSoloMother }} Days</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endsection

    @push('page_scripts')
        <script>
            $(document).ready(function() {

            })

            function approveLeave(id) {
                var signatoryId = $("approveBtn").attr('sig-id')

                Swal.fire({
                    title: 'Approval Confirmation',
                    text: 'Approve this leave application?',
                    showDenyButton: true,
                    confirmButtonText: 'Approve',
                    denyButtonText: `Close`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('leaveApplications.approve-ajax') }}",
                            type: 'GET',
                            data: {
                                id: id,
                                SignatoryId: "{{ $leave->SignatoryId }}",
                            },
                            success: function(suc) {
                                Toast.fire({
                                    text: 'Leave approved',
                                    icon: 'success'
                                })
                                window.location.replace("{{ route('leaveApplications.my-approvals') }}");
                            },
                            error: function(err) {
                                Swal.fire({
                                    text: 'Error approving leave! Contact IT support for more.',
                                    icon: 'error'
                                })
                            }
                        })
                    } else if (result.isDenied) {

                    }
                })
            }

            function rejectLeave(id, signatoryId) {
                (async () => {
                    const {
                        value: text
                    } = await Swal.fire({
                        input: 'textarea',
                        inputLabel: 'Remarks/Notes',
                        inputPlaceholder: 'Type your remarks here...',
                        inputAttributes: {
                            'aria-label': 'Type your remarks here'
                        },
                        title: 'Reject This Leave?',
                        text: 'Before you reject this leave, please provide a remark or comment so the employee can assess the situation further.',
                        showCancelButton: true
                    })

                    if (text) {
                        $.ajax({
                            url: "{{ route('leaveApplications.reject-leave-ajax') }}",
                            type: "GET",
                            data: {
                                id: id,
                                SignatoryId: signatoryId,
                                Notes: text,
                            },
                            success: function(res) {
                                Toast.fire({
                                    icon: 'info',
                                    text: 'Leave rejected!'
                                })
                                $('#card-' + id).remove()
                            },
                            error: function(err) {
                                Swal.fire({
                                    icon: 'error',
                                    text: 'Error rejecting leave!'
                                })
                            }
                        })
                    }
                })()
            }
        </script>
    @endpush
