@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;
@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12" style="margin-bottom: 26px;">
            <p class="text-center no-pads text-lg">Leave Credit Management Dashboard</p>
            <p class="text-center no-pads text-muted">Manage and view your leave credit activity and logs</p>
        </div>

        {{-- CONTENT LINEAR --}}
        <div class="col-lg-8 offset-lg-2">
            {{-- leave credit balances --}}
            <div class="section">
                <div class="row">
                    <div class="col-10">
                        <p class="no-pads text-md">Your leave balances</p>
                        <p class="no-pads text-muted">Available leave balances as of this month. This is where you can benchmark your available balances if you desire to go on a vacation, or convert available balances into cash.</p>
                    </div>
                    <div class="col-2 center-contents">
                        <img style="width: 90% !important;" class="img-fluid" src="{{ asset('imgs/leave-balances.png') }}" alt="User profile picture">
                    </div>
                </div>
    
                <div class="card shadow-none mt-4">
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <th>Leave</th>
                                <th class="text-right">Total Available</th>
                                <th class="text-right">Available for Conversion</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-muted">Vacation leave credits</td>
                                    <td class="text-right">{{ $leaveBalances != null ? number_format($leaveBalances->Vacation, 1) . ' days' : '-' }}</td>
                                    <td class="text-right">{{ $leaveBalances != null ? ($leaveBalances->Vacation >= 15 ? number_format($leaveBalances->Vacation - 15) . ' days' : '-') : '-' }}</td>
                                    <td class="text-right">
                                        <button onclick="showModal(`Vacation`)" class="btn btn-link-muted btn-sm" title="View logs"><i class="fas fa-chevron-right"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Sick leave credits</td>
                                    <td class="text-right">{{ $leaveBalances != null ? number_format($leaveBalances->Sick, 1) . ' days' : '-' }}</td>
                                    <td class="text-right">{{ $leaveBalances != null ? ($leaveBalances->Sick > 150 ? number_format($leaveBalances->Sick - 151) . ' days' : '-') : '-' }}</td>
                                    <td class="text-right">
                                        <button onclick="showModal(`Sick`)" class="btn btn-link-muted btn-sm" title="View logs"><i class="fas fa-chevron-right"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Special leave credits</td>
                                    <td class="text-right">{{ $leaveBalances != null ? number_format($leaveBalances->Special, 1) . ' days' : '-' }}</td>
                                    <td class="text-right">-</td>
                                    <td class="text-right">
                                        <button onclick="showModal(`Special`)" class="btn btn-link-muted btn-sm" title="View logs"><i class="fas fa-chevron-right"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Solo parent leave credits</td>
                                    <td class="text-right">{{ $leaveBalances != null ? number_format($leaveBalances->SoloParent, 1) . ' days' : '-' }}</td>
                                    <td class="text-right">-</td>
                                    <td class="text-right">
                                        <button onclick="showModal(`SoloParent`)" class="btn btn-link-muted btn-sm" title="View logs"><i class="fas fa-chevron-right"></i></button>
                                    </td>
                                </tr>
                                @if ($employees->Gender == 'Male')
                                    <tr>
                                        <td class="text-muted">Paternity leave credits</td>
                                        <td class="text-right">{{ $leaveBalances != null ? number_format($leaveBalances->Paternity, 1) . ' days' : '-' }}</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">
                                            <button onclick="showModal(`Paternity`)" class="btn btn-link-muted btn-sm" title="View logs"><i class="fas fa-chevron-right"></i></button>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-muted">Maternity leave credits</td>
                                        <td class="text-right">{{ $leaveBalances != null ? number_format($leaveBalances->Maternity, 1) . ' days' : '-' }}</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">
                                            <button onclick="showModal(`Maternity`)" class="btn btn-link-muted btn-sm" title="View logs"><i class="fas fa-chevron-right"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Solo mom leave credits</td>
                                        <td class="text-right">{{ $leaveBalances != null ? number_format($leaveBalances->MaternityForSoloMother, 1) . ' days' : '-' }}</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">
                                            <button onclick="showModal(`MaternityForSoloMother`)" class="btn btn-link-muted btn-sm" title="View logs"><i class="fas fa-chevron-right"></i></button>
                                        </td>
                                    </tr>
                                @endif     
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('leaveApplications.create') }}" class="btn btn-primary">File a Leave <i class="fas fa-external-link-alt ico-tab-left-mini"></i></a>

                        <div class="dropdown float-right">
                            <a class="btn btn-primary-skinny dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                              More Options
                            </a>
                          
                            <div class="dropdown-menu">
                                <button class="dropdown-item" data-toggle="modal" data-target="#modal-leave-logs">Leave credit logs</button>
                                <button class="dropdown-item" data-toggle="modal" data-target="#modal-leave-conversion-logs">Leave conversion logs</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- all leave --}}
            <div class="section">
                <div class="row">
                    <div class="col-10">
                        <p class="no-pads text-md">All leave applications</p>
                        <p class="no-pads text-muted">All of your leave history are listed here. Filter and sort it according to your liking.</p>
                    </div>
                    <div class="col-2 center-contents">
                        <img style="width: 80% !important;" class="img-fluid" src="{{ asset('imgs/leave-history.png') }}" alt="User profile picture">
                    </div>
                </div>

                <div class="card shadow-none mt-4">
                    <div class="card-body">
                        <div id="app">
                            <all-leave></all-leave>
                        </div>
                        @vite('resources/js/app.js')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection

@include('employees.modal_leave_balance_details')
@include('employees.modal_leave_conversion_logs')
@include('my_account.modal_leave_individual_logs')

@push('page_scripts')
    <script>
        $(document).ready(function() {

        });

        function showModal(type) {
            $('#modal-leave-individual-logs').modal('show')

            $('#logs-title').text(type + ' Leave History')
            $('#leave-loader').removeClass('gone')
            $('#all-leave-table tbody tr').remove()
            $.ajax({
                url : "{{ route('leaveApplications.get-leaves-by-type') }}",
                type : "GET",
                data : {
                    EmployeeId : "{{ $employees->id }}",
                    LeaveType : type,
                },
                success : function(res) {
                    $('#leave-loader').addClass('gone')

                    $.each(res, function(index, element) {
                        $('#all-leave-table tbody').append(`
                            <tr style='cursor: pointer;' onclick='view("` + res[index]['id'] + `")'>
                                <td><i class='fas ` + getIconType(res[index]['LeaveType']) + `'></i></td>
                                <td>` + moment(res[index]['created_at']).format('MMMM DD, YYYY') + `</span></td>
                                <td>` + res[index]['Content'] + `</td>
                                <td class='text-right'>` + res[index]['TotalDays'] + `</td>
                                <td class='text-center'><span class='badge ` + getStatusBadgeColor(res[index]['Status']) + `'>` + res[index]['Status'] + `</td>
                            </tr>
                        `)
                    })
                },
                error : function(err) {
                    $('#leave-loader').addClass('gone')
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting leave conversions history'
                    })
                }
            })
        }

        function getIconType(leaveType) {
            if (leaveType === 'Vacation') {
                return 'fa-umbrella-beach'
            } else if (leaveType === 'Sick') {
                return 'fa-clinic-medical'
            } else if (leaveType === 'Special') {
                return 'fa-birthday-cake'
            } else if (leaveType === 'Paternity') {
                return 'fa-male'
            } else if (leaveType === 'Maternity' | leaveType === 'MaternityForSoloMother') {
                return 'fa-female'
            } else if (leaveType === 'SoloParent') {
                return 'fa-suitcase-rolling'
            } else {
                return 'fa-circle'
            }
        }

        function getStatusBadgeColor(status) {
            if (isNull(status)) {
                return 'bg-info'
            } else if (status === 'Filed') {
                return 'bg-primary'
            } else if (status == 'REJECTED') {
                return 'bg-danger'
            } else {
                return 'bg-success'
            }
        }

        function view(id) {
            window.location.href = `{{ url('/my_account/view-leave') }}/` + id
        }
    </script>
@endpush
