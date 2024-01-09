@php
    use App\Models\Employees;
    use App\Models\Users;
    use App\Models\LeaveDays;
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
        @foreach ($leaves as $item)
            @php
                $leaveDays = LeaveDays::where('LeaveId', $item->id)->orderBy('LeaveDate')->get();
            @endphp
            <div id="card-{{ $item->id }}" class="col-lg-4 col-md-6 col-sm-12">
                <div class="card shadow-none">
                    <div class="card-header border-0">
                        <span class="card-title text-info"><i class="fas fa-info-circle ico-tab"></i>{{ Employees::getMergeName($item) }}</span>
                    </div>
                    <div class="card-body">
                        <p style="margin: 0; padding: 0; font-size: 1.3em;">{{ $item->Content }}</p>
                        <div class="divider"></div>
                        <span class="text-muted float-left">Leave Date(s):</span>
                        <br>
                        <ul>
                            @foreach ($leaveDays as $days)
                                <li><strong>{{ date('D, M d, Y', strtotime($days->LeaveDate)) }}</strong> <span class="text-muted">({{ $days->Duration }})</span></li>
                            @endforeach
                        </ul>
                        <span class="text-muted">No. of Days: <strong>{{ count($leaveDays) }}</strong></span>
                        <br>
                        <span class="text-muted">Date Filed: <strong>{{ date('D, M d, Y', strtotime($item->created_at)) }}</strong></span>
                    </div>
                    <div class="card-footer">
                        <button id="{{ $item->id }}" class="btn btn-sm btn-success" onclick="approveLeave('{{ $item->id }}')" sig-id="{{ $item->SignatoryId }}"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                        <a href="" class="btn btn-sm btn-danger float-right"><i class="fas fa-exclamation-circle ico-tab-mini"></i>Reject</a>
                    </div>
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
                text : 'Approve this leave application?',
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: `Close`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('leaveApplications.approve-ajax') }}",
                        type : 'GET',
                        data : {
                            id : id,
                            SignatoryId : signatoryId,
                        },
                        success : function(suc) {
                            Toast.fire({
                                text : 'Leave approved',
                                icon : 'success'
                            })
                            $('#card-' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                text : 'Error approving leave! Contact IT support for more.',
                                icon : 'error'
                            })
                        }
                    })
                } else if (result.isDenied) {
                    
                }
            })
        }
    </script>
@endpush