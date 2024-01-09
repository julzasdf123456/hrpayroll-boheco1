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
                    <p class="badge bg-warning" style="padding: 10px;">{{ $leaveApplication->Status }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-10 offset-lg-1 col-md-12">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title">
                        @if ($leaveApplication->Status == 'APPROVED')
                            <i class="fas fa-check-circle ico-tab text-success"></i>
                        @else
                            <i class="fas fa-info-circle ico-tab text-warning"></i>
                        @endif
                        <strong>{{ Employees::getMergeName(Employees::find($leaveApplication->EmployeeId)) }}</strong>
                    </span>

                    <div class="card-tools">
                        <span class='text-muted'>Date Filed: </span>{{ date('F d, Y', strtotime($leaveApplication->created_at)) }}
                    </div>
                </div>
                <div class="card-body">
                    <p><span class="text-muted">Leave Type: </span> <span class="badge bg-info" style="padding: 10px;">{{ $leaveApplication->LeaveType }}</span></p>
                    <p style="font-size: 1.4em;"><span class="text-muted">Reason: </span> <strong style="border-bottom: 1px solid #242424">{{ $leaveApplication->Content }}</strong></p>
                    <span class="text-muted">Dates of Leave:</span>
                    <br>
                    <ul>
                        @foreach ($leaveDays as $item)
                            <li>{{ date('D, F d, Y', strtotime($item->LeaveDate)) }} ({{ $item->Duration }})
                                @if ($leaveApplication->Status == 'APPROVED' | $leaveApplication->Status == 'FOR REVIEW')
                                    
                                @else
                                    <button class="btn btn-xs btn-danger" style="margin-left: 20px;"><i class="fas fa-trash"></i></button>
                                @endif                                
                            </li>
                        @endforeach
                    </ul>

                    {{-- FOR SICK LEAVE --}}
                    @if ($leaveApplication->LeaveType == 'Sick')
                        <div class="divider"></div>
                        <input type="file" accept="image/png, image/gif, image/jpeg" id="img-attachment" style="display: none"/>
                        <button class="btn btn-sm btn-primary float-right" onclick="thisFileUpload()"><i class="fas fa-upload ico-tab-mini"></i>Upload Medical Certificate</button>
                        <p class="text-muted">Medical Certificate Attachment(s)</p>

                        <div class="row" id="imgs-data">
                            @foreach ($leaveImgs as $item)
                                <div class="col-md-3" id="{{ $item->id }}">
                                    <button class="btn btn-xs btn-danger" style="position: absolute; right: 10px; top: 5px;" onclick="removeImg('{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <img src="{{ $item->HexImage }}" style="width: 100%;" alt="">
                                </div>
                                
                            @endforeach
                        </div>

                        <div class="divider"></div>
                    @endif

                    <div class="row justify-content-around">
                        @foreach ($leaveSignatories as $item)
                            @if (Auth::id() == $item->EmployeeId)
                                <div class="col-5" style="margin-top: 20px;">
                                    <p class="text-center" style="padding: 0 !important; margin: 0 !important;">
                                        <u><strong>{{ Employees::getMergeName($item) }}</strong></u>
                                        
                                    </p>
                                    <address class="text-center"><i>{{ $item->Position }}</i></address>
                                    <p class="text-center">
                                        @if ($item->Status == 'APPROVED')

                                        @else
                                            <a href="{{ route('leaveApplications.approve-leave', [$leaveApplication->id, $item->id]) }}" class="btn btn-xs btn-primary text-center"><i class="fas fa-check-circle ico-tab-mini"></i>Approve This Leave</a>
                                        @endif                                        
                                    </p>                                  
                                </div>  
                            @else
                                <div class="col-5" style="margin-top: 20px;">
                                    @if ($item->Status == 'APPROVED')
                                        <p class="text-center" style="padding: 0 !important; margin: 0 !important;">
                                            <u><strong>{{ Employees::getMergeName($item) }}</strong></u>
                                            
                                        </p>
                                        <address class="text-center"><i>{{ $item->Position }}</i></address>
                                        <p class="text-center"><button class="btn btn-xs btn-success text-center"><i class="fas fa-check-circle ico-tab-mini"></i>Approved</button></p>
                                    @else
                                        <p class="text-center text-muted" style="padding: 0 !important; margin: 0 !important;">
                                            <u><strong>{{ Employees::getMergeName($item) }}</strong></u>
                                            
                                        </p>
                                        <address class="text-center text-muted"><i>{{ $item->Position }}</i></address>
                                        <p class="text-center"><button class="btn btn-xs btn-default text-center"><i class="fas fa-info-circle ico-tab-mini"></i>Unapproved</button></p>
                                    @endif
                                </div>                               
                            @endif
                            
                        @endforeach
                        
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('home') }}" class="btn btn-default">Home</a>
                    <button class="btn btn-danger float-right" id="deleteLeave"><i class="fas fa-trash ico-tab-mini"></i>Trash Leave</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#img-attachment').on('change', function() {
                readURL(this)
            })

            $('#deleteLeave').on('click', function(e) {
                e.preventDefault()

                Swal.fire({
                    title: 'Deletion Confirmation',
                    text : 'You sure you wanna delete this leave?',
                    showDenyButton: true,
                    confirmButtonText: 'Delete',
                    denyButtonText: `Close`,
                    }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('leaveApplications.delete-leave') }}",
                            type : 'GET',
                            data : {
                                id : "{{ $leaveApplication->id }}",
                            },
                            success : function(suc) {
                                Toast.fire({
                                    text : 'Leave deleted!',
                                    icon : 'success'
                                })
                                window.location.href = "{{ route('home') }}"
                            },
                            error : function(err) {
                                Swal.fire({
                                    text : 'Error deleting leave! Contact IT support for more.',
                                    icon : 'error'
                                })
                            }
                        })
                    } else if (result.isDenied) {
                        
                    }
                })
            })
        })

        function thisFileUpload() {
            document.getElementById("img-attachment").click();
        };

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $.ajax({
                        url : "{{ route('leaveApplications.add-image-attachments') }}",
                        type : 'POST',
                        data : {
                            _token : "{{ csrf_token() }}",
                            LeaveId : "{{ $leaveApplication->id }}",
                            HexImage : encodeURIComponent(e.target.result),
                        },
                        success : function(res) {
                            $('#imgs-data').append(
                                "<div class='col-md-3' id='" + res['id'] + "'>" +
                                    "<button class='btn btn-xs btn-danger' style='position: absolute; right: 10px; top: 5px;' onclick=removeImg('" + res['id'] + "')><i class='fas fa-trash'></i></button>" +
                                    "<img src='" + res['HexImage'] + "' style='width: 100%;'/>" +
                                "</div>"
                            )
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error uploading image attachment'
                            })
                        }
                    })
                    
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImg(id) {
            Swal.fire({
                title: 'Remove Confirmation',
                text : 'You sure you wanna remove this image attachment?',
                showDenyButton: true,
                confirmButtonText: 'Remove',
                denyButtonText: `Close`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('leaveApplications.remove-image') }}",
                        type : 'GET',
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error removing image attachment'
                            })
                        }
                    })
                } else if (result.isDenied) {
                    
                }
            })            
        }
    </script>
@endpush