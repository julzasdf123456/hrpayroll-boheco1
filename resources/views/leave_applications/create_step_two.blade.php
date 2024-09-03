@php
    use App\Models\Employees;
    use App\Models\LeaveBalances;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p><strong>Step 2:</strong> File for Leave Application</p>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-4">           
            {{-- ADD DAYS --}}
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title">Select Days 
                        <br> 
                        @if ($leaveApplication->LeaveType === 'Vacation' | $leaveApplication->LeaveType === 'Sick')
                            <strong class="text-muted">({{ $leaveApplication->LeaveType }} available: {{ LeaveBalances::toExpanded($leaveBalance->Balance) }})</strong></span>
                        @else
                            <strong class="text-muted">({{ $leaveApplication->LeaveType }} available days: {{ $leaveBalance->Balance }})</strong></span>
                        @endif
                        
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <input type="text" name="Day" id="Day" class="form-control"/>
                    </div>

                    <table id="dates-table" class="table table-hover table-borderless table-sm">
                        <tbody>
                            @foreach ($leaveDays as $item)
                                <tr id='{{ $item->id }}'>
                                    <td>{{ date('D, M d, Y', strtotime($item->LeaveDate)) }}</td>
                                    <td>
                                        <select id="longevity-{{ $item->id }}" class='form-control form-control-sm'>
                                            <option value='WHOLE' {{ $item->Duration=='WHOLE' ? 'selected' : '' }}>Whole Day</option>
                                            <option value='AM' {{ $item->Duration=='AM' ? 'selected' : '' }}>Morning Only</option>
                                            <option value='PM' {{ $item->Duration=='PM' ? 'selected' : '' }}>Afternoon Only</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class='btn btn-xs btn-danger float-right' onclick="deleteDate('{{ $item->id }}')"><i class='fas fa-trash'></i></button>
                                        <button class='btn btn-xs btn-primary float-right' onclick="updateLongevity('{{ $item->id }}')"><i class='fas fa-check-circle'></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @push('page_scripts')
                        <script>
                            var holidays = "{{ $holidays }}"
                            holidays = holidays.split(',')
                            
                            $('#Day').daterangepicker({
                                showDropdowns: true,
                                alwaysShowCalendars: true,
                                isInvalidDate: function(date) {
                                    if (date.day() == 0 | holidays.includes(date.format('YYYY-MM-DD'))) {
                                        return true
                                    } else {
                                        return false
                                    }
                                },
                                minYear: 1901,
                                maxYear: parseInt(moment().format('YYYY'),10)
                            }, function(start, end, label) {
                                addDates(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
                            });
                        </script>
                    @endpush
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-none">
                <div class="card-body">
                    <span class="text-muted"><i>Preview</i></span>
                    <h4 class="text-center px-0 mx-0"><strong>{{ env('APP_COMPANY') }}</strong></h4>
                    <p class="text-center">{{ env('APP_ADDRESS') }}</p>

                    <span>
                        <strong>Date Filed: </strong>{{ date('F d, Y', strtotime($leaveApplication->created_at)) }}
                        {{-- <br>
                        <strong>Days of Leave: </strong>{{ date('F d, Y', strtotime($leaveApplication->DateFrom)) }}{{ $leaveApplication->DateTo==null ? '' : (' to ' . date('F d, Y', strtotime($leaveApplication->DateTo))) }} ({{ $leaveApplication->NumberOfDays . ' days' }}) --}}
                    </span>
                    <br>
                    <p><strong>Purpose/Reason: </strong> {{ $leaveApplication->Content }}</p>

                    {{-- FOR SICK LEAVE, ADD IMAGE ATTACHMENTS --}}
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

                    <br>
                    <div class="row justify-content-around">
                        @foreach ($leaveSignatories as $item)
                            <div class="col-5" style="margin-top: 20px;">
                                <p class="text-center" style="padding: 0 !important; margin: 0 !important;">
                                    <u><strong>{{ Employees::getMergeName($item) }}</strong></u>
                                    {{-- <button class="btn btn-xs float-right text-danger" title="Delete this signatory" onclick="deleteSignatory({{ $item->id }})"><i class="fas fa-trash"></i></button> --}}
                                </p>
                                <address class="text-center"><i>{{ $item->Position }}</i></address>                                
                            </div>
                        @endforeach
                        
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-danger" id="deleteLeave"><i class="fas fa-trash ico-tab-mini"></i>Trash Leave</button>
                    <a href="{{ route('leaveApplications.publish-leave', [$leaveApplication->id]) }}" class="btn btn-primary float-right"><i class="fas fa-check-circle ico-tab-mini"></i>Publish Leave</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        function deleteSignatory(id) {
            if (confirm('Are you sure you want to delete this signatory?')) {
                $.ajax({
                    url : "{{ route('leaveApplications.remove-leave-signatory') }}",
                    type : 'GET',
                    data : {
                        id : id
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error has occurred while attempting to remove signatory.')
                    }
                })
            }
        }

        function deleteDate(id) {
            Swal.fire({
                title: 'Delete Confirmation',
                text : 'Are you sure you want to remove this date?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ url('/leaveDays') }}/" + id,
                        type : 'DELETE',
                        data : {
                            _token : "{{ csrf_token() }}"
                        },
                        success : function(res) {
                            $('#' + id).remove()
                            Toast.fire({
                                icon : 'success',
                                text : 'Date removed'
                            })
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error deleting date'
                            })
                        }
                    })
                } else if (result.isDenied) {
                    
                }
            })            
        }

        function updateLongevity(id) {
            var longevity = $('#longevity-' + id).val()

            $.ajax({
                url : "{{ route('leaveDays.update-longevity') }}",
                type : 'GET',
                data : {
                    id : id,
                    Longevity : longevity,
                    LeaveId : "{{ $leaveApplication->id }}"
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Leave duration updated'
                    })
                },
                error : function(xhr, statusText, err) {
                    if (xhr.status == 401) {
                        Swal.fire({
                            icon : 'warning',
                            text : 'Insufficient Leave Credit Balance'
                        })
                    } else {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error updating leave duarion'
                        })
                    }
                    $('#longevity-' + id).val(longevity).change()
                }
            })
        }

        function addDates(start, end) {
            $.ajax({
                url : "{{ route('leaveDays.add-days') }}",
                type : 'GET',
                data : {
                    LeaveId : "{{ $leaveApplication->id }}",
                    From : start,
                    To : end,
                },
                success : function(res) {
                    $('#dates-table tbody').append(res)
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error adding dates'
                    })
                }
            })
        }

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

        $(document).ready(function() {            
            $('#add-signatory').on('click', function() {
                $.ajax({
                    url : "{{ url('/leave_applications/add-signatories') }}",
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        UserId : $('#signatories').val(),
                        LeaveId : "{{ $leaveApplication->id }}",
                    },
                    success : function(response) {
                        if (response['response'] == 'exists') {
                            alert('This signatory is already added to this leave!');
                        } else {
                            location.reload()
                        }
                    },
                    error : function(error) {
                        alert('An error has occurred while attempting to add signatory.')
                    }
                })
            })

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
    </script>
@endpush