@php
    use App\Models\Employees;
    use App\Models\Users;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Attendance Confirmation Approvals</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Employee</th>
                            <th>Reason of Inability<br>to Time-in/Time-out</th>
                            <th>AM In</th>
                            <th>AM Out</th>
                            <th>PM In</th>
                            <th>PM Out</th>
                            <th>OT In</th>
                            <th>OT Out</th>
                            <th>Prepared By</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($ats as $item)
                                <tr id="{{ $item->id }}">
                                    <td>{{ Employees::getMergeName($item) }}</td>
                                    <td>{{ $item->Reason }}</td>
                                    <td>{{ $item->AMIn != null ? date('M d, Y h:i A', strtotime($item->AMIn)) : '' }}</td>
                                    <td>{{ $item->AMOut != null ? date('M d, Y h:i A', strtotime($item->AMOut)) : '' }}</td>
                                    <td>{{ $item->PMIn != null ? date('M d, Y h:i A', strtotime($item->PMIn)) : '' }}</td>
                                    <td>{{ $item->PMOut != null ? date('M d, Y h:i A', strtotime($item->PMOut)) : '' }}</td>
                                    <td>{{ $item->OTIn != null ? date('M d, Y h:i A', strtotime($item->OTIn)) : '' }}</td>
                                    <td>{{ $item->OTOut != null ? date('M d, Y h:i A', strtotime($item->OTOut)) : '' }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <button onclick="reject(`{{ $item->id }}`)" class="btn btn-sm btn-danger float-right" style="margin-left: 5px;"><i class="fas fa-times-circle ico-tab-mini"></i>Reject</button>
                                        <button onclick="approve(`{{ $item->id }}`)" class="btn btn-sm btn-success float-right"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function approve(id) {
            Swal.fire({
                title: 'Approval Confirmation',
                text : 'Approve this attendance confirmation?',
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: `Close`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('attendanceConfirmations.approve') }}",
                        type : 'GET',
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                text : 'Attendance confirmation approved',
                                icon : 'success'
                            })
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                text : 'Error approving attendance confirmation! Contact IT support for more.',
                                icon : 'error'
                            })
                        }
                    })
                } 
            })
        }

        function reject(id) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Remarks/Notes',
                    inputPlaceholder: 'Type your remarks here...',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Reject This Attendance Confirmation?',
                    text : 'Before you reject this attendance confirmation, please provide a remark or comment so the employee can assess the situation further.',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : "{{ route('attendanceConfirmations.reject') }}",
                        type : "GET",
                        data : {
                            id : id, 
                            Notes : text, 
                        }, 
                        success : function(res) {
                            Toast.fire({
                                icon : 'info',
                                text : 'Attendance confirmation rejected!'
                            })                            
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error rejecting attendance confirmation!'
                            })
                        }
                    })
                }
            })()
        }
    </script>
@endpush