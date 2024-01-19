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
                    <h4>Offset Application Approvals</h4>
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
                            <th>Date of Duty</th>
                            <th>Purpose/Reason of Duty</th>
                            <th>Date Of Offset</th>
                            <th>Reason of Offset</th>
                            <th>Prepared By</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($offsets as $item)
                                <tr id="{{ $item->id }}">
                                    <td>{{ Employees::getMergeName($item) }}</td>
                                    <td>{{ date('M d, Y', strtotime($item->DateOfDuty)) }}</td>
                                    <td>{{ $item->PurposeOfDuty }}</td>
                                    <td>{{ date('M d, Y', strtotime($item->DateOfOffset)) }}</td>
                                    <td>{{ $item->OffsetReason }}</td>
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
                text : 'Approve this offset application?',
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: `Close`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('offsetApplications.approve') }}",
                        type : 'GET',
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                text : 'Offset approved',
                                icon : 'success'
                            })
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                text : 'Error approving offset! Contact IT support for more.',
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
                    title: 'Reject This Offset?',
                    text : 'Before you reject this offset, please provide a remark or comment so the employee can assess the situation further.',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : "{{ route('offsetApplications.reject') }}",
                        type : "GET",
                        data : {
                            id : id, 
                            Notes : text, 
                        }, 
                        success : function(res) {
                            Toast.fire({
                                icon : 'info',
                                text : 'Offset rejected!'
                            })                            
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error rejecting offset!'
                            })
                        }
                    })
                }
            })()
        }
    </script>
@endpush