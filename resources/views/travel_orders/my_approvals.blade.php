@php
    use App\Models\Employees;
    use App\Models\Users;
    use App\Models\TravelOrderDays;
    use Illuminate\Support\Facades\DB;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>My Travel Order Approvals</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        @foreach ($travels as $item)
            @php
                $travelDays = TravelOrderDays::where('TravelOrderId', $item->id)->orderBy('Day')->get();
                $employees = DB::table('TravelOrderEmployees')
                    ->leftJoin('Employees', 'TravelOrderEmployees.EmployeeId', '=', 'Employees.id')
                    ->whereRaw("TravelOrderEmployees.TravelOrderId='" . $item->id . "'")
                    ->select("FirstName",
                        "MiddleName",
                        "LastName",
                        "Suffix")
                    ->orderBy("FirstName")
                    ->get();
            @endphp
            <div id="card-{{ $item->id }}" class="col-lg-4 col-md-6 col-sm-12">
                <div class="card shadow-none">
                    <div class="card-header border-0">
                        <span class="text-muted"><i class="fas fa-map-marker-alt ico-tab-mini"></i>{{ $item->Destination }}</span>
                        <p title="{{ $item->Purpose }}" class="ellipsize-2 text-md">{{ $item->Purpose }}</p>
                    </div>
                    <div class="card-body">
                        <span class="text-muted float-left">Employees to Travel:</span>
                        <br>
                        <ul>
                            @foreach ($employees as $employee)
                                <li><strong>{{ Employees::getMergeName($employee) }}</strong></li>
                            @endforeach
                        </ul>

                        <span class="text-muted float-left">Travel Date(s):</span>
                        <br>
                        <ul>
                            @foreach ($travelDays as $days)
                                <li>{{ date('D, M d, Y', strtotime($days->Day)) }}</li>
                            @endforeach
                        </ul>
                        <br>
                        <span class="text-muted">Date Filed: {{ date('D, M d, Y', strtotime($item->DateFiled)) }}</span>
                        <br>
                        <span class="text-muted">Filed By: {{ Employees::getMergeName($item) }}</span>
                    </div>
                    <div class="card-footer">
                        <button id="{{ $item->id }}" class="btn btn-sm btn-primary" onclick="approveTravelOrder(`{{ $item->id }}`)" sig-id="{{ $item->SignatoryId }}"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                        <button onclick="rejectLeave(`{{ $item->id }}`, `{{ $item->SignatoryId }}`)" class="btn btn-sm btn-danger float-right"><i class="fas fa-times-circle ico-tab-mini"></i>Reject</button>
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

        function approveTravelOrder(id) {
            var signatoryId = $('#' + id).attr('sig-id')

            Swal.fire({
                title: 'Approval Travel Order',
                text : 'Approve this travel order?',
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: `Close`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('travelOrders.approve-ajax') }}",
                        type : 'GET',
                        data : {
                            id : id,
                            SignatoryId : signatoryId,
                        },
                        success : function(suc) {
                            Toast.fire({
                                text : 'Travel order approved',
                                icon : 'success'
                            })
                            $('#card-' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                text : 'Error approving travel order! Contact IT support for more.',
                                icon : 'error'
                            })
                        }
                    })
                } else if (result.isDenied) {
                    
                }
            })
        }

        function rejectLeave(id, signatoryId) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Remarks/Notes',
                    inputPlaceholder: 'Type your remarks here...',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Reject This Leave?',
                    text : 'Before you reject this leave, please provide a remark or comment so the employee can assess the situation further.',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : "{{ route('leaveApplications.reject-leave-ajax') }}",
                        type : "GET",
                        data : {
                            id : id, 
                            SignatoryId : signatoryId,
                            Notes : text, 
                        }, 
                        success : function(res) {
                            Toast.fire({
                                icon : 'info',
                                text : 'Leave rejected!'
                            })                            
                            $('#card-' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error rejecting leave!'
                            })
                        }
                    })
                }
            })()
        }
    </script>
@endpush