@php
    use App\Models\Employees;
    use App\Models\Users;
@endphp
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                @if ($leaveApplication->Status == 'APPROVED')
                    <p class="badge bg-success" style="padding: 10px;">{{ $leaveApplication->Status }}</p>
                @elseif ($leaveApplication->Status == 'REJECTED')
                    <p class="badge bg-danger" style="padding: 10px;">{{ $leaveApplication->Status }}</p>
                @else
                    <p class="badge bg-info" style="padding: 10px;">{{ $leaveApplication->Status }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header border-0">
                <span class="card-title">
                    @if ($leaveApplication->Status == 'APPROVED')
                        <i class="fas fa-check-circle ico-tab text-success"></i>
                    @elseif ($leaveApplication->Status == 'REJECTED')
                        <i class="fas fa-exclamation-circle ico-tab text-danger"></i>
                    @else
                        <i class="fas fa-info-circle ico-tab text-warning"></i>
                    @endif
                    <strong>{{ Employees::getMergeName(Employees::find($leaveApplication->EmployeeId)) }}</strong>
                    <span class="badge bg-info" style="padding: 8px; margin-left: 8px;">{{ $leaveApplication->LeaveType }}</span>
                </span>

                <div class="card-tools">
                    
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    {{-- DETAILS --}}
                    <div class="col-lg-8">
                        <table class="table table-hover table-sm table-bordered">
                            <tr>
                                <td class="text-muted">Reason</td>
                                <td>
                                    {{ $leaveApplication->Content }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date Filed</td>
                                <td>
                                    {{ date('F d, Y h:i A', strtotime($leaveApplication->created_at)) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Dates of Leave</td>
                                <td>
                                    <ul>
                                        @foreach ($leaveDays as $item)
                                            <li>{{ date('D, F d, Y', strtotime(
                                                str_replace(':AM',' AM',str_replace(':PM',' PM',$item->LeaveDate))
                                                )) }} ({{ $item->Duration }})
                                                @if ($leaveApplication->Status == 'APPROVED' | $leaveApplication->Status == 'FOR REVIEW')
                                                    
                                                @else
                                                    <button class="btn btn-xs btn-link text-danger" style="margin-left: 20px;"><i class="fas fa-trash"></i></button>
                                                @endif                                
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        </table>                    

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
                    </div>
                    {{-- LOGS --}}
                    <div class="col-lg-4">
                        <p class="text-muted">Signatory Logs</p>
                        @foreach ($leaveSignatories as $item)
                            <div class="mb-2 p-2 {{ Auth::user()->ColorProfile != null ? 'border-left-dark' : 'border-left-light' }}">
                                @if ($item->Status === 'APPROVED')
                                    <span class="badge bg-success">{{ $item->Status }}</span>
                                @elseif ($item->Status === 'REJECTED')
                                    <span class="badge bg-danger">{{ $item->Status }}</span>
                                @else
                                    <span class="badge {{ Auth::user()->ColorProfile != null ? 'bg-white' : 'bg-dark' }}">{{ $item->Status == null ? 'PENDING' : $item->Status }}</span>
                                @endif
                                
                                <span style="font-size: .85em;" class="text-muted float-right">{{ date('M d, Y h:i A', strtotime($item->updated_at)) }}</span>
                                <br>
                                <p class="no-pads"><strong>{{ Employees::getMergeName($item) }}</strong></p>
                                @if ($item->Notes != null)
                                    <span class="text-muted">{{ $item->Notes }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    {{-- SIGNATORES --}}
                    <div class="col-lg-12">
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
                </div>

            </div>
            <div class="card-footer">
                <a href="{{ route('home') }}" class="btn btn-default">Home</a>
                @php
                    $earliestLeaveDay = $leaveDays[0];
                    $earliestDate = null;

                    if ($earliestLeaveDay != null) {
                        $earliestDate = date('Y-m-d', strtotime($earliestLeaveDay->LeaveDate));
                    }
                @endphp
                @if ($earliestDate != null && $earliestDate > date('Y-m-d')/* && $leaveApplication->Status !== 'APPROVED'*/)
                    <button class="btn btn-danger float-right" id="deleteLeave"><i class="fas fa-trash ico-tab-mini"></i>Trash Leave</button>
                @else
                    <span class="float-right text-muted">This leave is no longer cancellable.</span>
                @endif
                
            </div>
        </div>
    </div>
</div>

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