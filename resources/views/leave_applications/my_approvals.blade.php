@php
    use App\Models\Employees;
    use Carbon\Carbon;
    use App\Models\LeaveDays;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>My Employees' Leaves for Approval</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        @foreach ($leaves as $item)
            @php
                $leaveDays = LeaveDays::where('LeaveId', $item->id)->orderBy('LeaveDate')->get();
                if ($item->Status == "Trashed") {
                    continue;
                }
            @endphp
            <div id="card-{{ $item->id }}" class="col-lg-4 col-md-6 col-sm-12">
                <div class="card shadow-none">
                    <div class="card-header border-0">
                        <span class="card-title">
                            <h4 class="no-pads"><strong>{{ Employees::getMergeName($item) }}</strong></h4>
                            <span class="badge bg-info">{{ $item->LeaveType }}</span>
                        </span>
                    </div>
                    <div class="card-body">
                        <p style="margin: 0; padding: 0; font-size: 1.3em;">{{ $item->Content }}</p>
                        <div class="divider"></div>
                            {{-- @foreach ($leaveDays as $days)
                                <li><strong>{{ Carbon::parse(str_replace(":AM"," AM",str_replace(":PM"," PM",$days->LeaveDate)))->format("M d, Y") }}</strong> <span
                                        class="text-muted">({{ $days->Duration }})</span></li>
                            @endforeach --}}
                            @if (count($leaveDays) > 1)
                            <span class="text-muted float-left">Leave Dates:</span>
                                <br>
                                <ul>
                                    <li><strong>{{ Carbon::parse(str_replace(":AM"," AM",str_replace(":PM"," PM",$leaveDays[0]->LeaveDate)))->format("M d, Y") }}</strong>
                                    <span> to </span><strong>{{ Carbon::parse(str_replace(":AM"," AM",str_replace(":PM"," PM",$leaveDays[count($leaveDays)-1]->LeaveDate)))->format("M d, Y") }}</strong></li>
                                </ul>
                            @else
                            <span class="text-muted float-left">Leave Date:</span>
                                <br>
                                <ul>
                                    <li><strong>{{ Carbon::parse(str_replace(":AM"," AM",str_replace(":PM"," PM",$leaveDays[0]->LeaveDate)))->format("M d, Y") }}</strong>
                                </ul>
                            @endif
                        <span class="text-muted">No. of Days: <strong>{{ count($leaveDays) }}</strong></span>
                        <br>
                        <span class="text-muted">Date Filed:
                            <strong>{{ date('D, M d, Y', strtotime($item->created_at)) }}</strong></span>
                            <br><br>
                            <a class="btn btn-sm btn-primary" href="/leave_applications/my-approvals/{{ $item->id }}">See More..</a>
                    </div>
                    {{-- <div class="card-footer">
                        <button id="{{ $item->id }}" class="btn btn-sm btn-success"
                            onclick="approveLeave(`{{ $item->id }}`)" sig-id="{{ $item->SignatoryId }}">
                            <i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                        <button onclick="rejectLeave(`{{ $item->id }}`, `{{ $item->SignatoryId }}`)"
                            class="btn btn-sm btn-danger float-right">
                            <i class="fas fa-times-circle ico-tab-mini"></i>Reject</button>
                    </div> --}}
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function approveLeave(id) {
            var signatoryId = $('#' + id).attr('sig-id')

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
                            SignatoryId: signatoryId,
                        },
                        success: function(suc) {
                            Toast.fire({
                                text: 'Leave approved',
                                icon: 'success'
                            })
                            $('#card-' + id).remove()
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
