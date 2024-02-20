@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
<div class="content px-3 row">
    <div class="col-lg-12">
        <p class="text-muted">NOTE: If the leave credit conversion requests are to be added in the Year-end incentives, do not mark them as paid. The system will automatically fetch all data to the SL and VL columns of the year-end incentives section.</p>
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Leave Credit Conversions for Check Vouching ({{ count($data) }})</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2" style="min-width: 220px;">Employee</th>
                            <th class="text-center" colspan="3">Vacation</th>
                            <th class="text-center" colspan="3">Sick</th>
                            <th class="text-center" rowspan="2">Total</th>
                            <th class="text-center" rowspan="2">Date Filed</th>
                            <th class="text-center" rowspan="2">Filed By</th>
                            <th class="text-center" rowspan="2"></th>
                        </tr>
                        <tr>
                            <th class="text-center">Available</th>
                            <th class="text-center">No. of Days</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Available</th>
                            <th class="text-center">No. of Days</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr id="{{ $item->id }}">
                                <td><strong>{{ Employees::getMergeNameFormal($item) }}</strong></td>
                                <td class="text-right">{{ number_format($item->Vacation, 2) }}</td>
                                <td class="text-right">{{ $item->VacationDays > 0 ? number_format($item->VacationDays) : '-' }}</td>
                                <td class="text-right">{{ $item->VacationAmount > 0 ? number_format($item->VacationAmount, 2) : '-' }}</td>
                                <td class="text-right">{{ number_format($item->Sick, 2) }}</td>
                                <td class="text-right">{{ $item->SickDays > 0 ? number_format($item->SickDays) : '-' }}</td>
                                <td class="text-right">{{ $item->SickAmount > 0 ? number_format($item->SickAmount, 2) : '-' }}</td>
                                <td><strong>{{ number_format($item->SickAmount + $item->VacationAmount, 2) }}</strong></td>
                                <td>{{ date('M d, Y h:i A', strtotime($item->created_at)) }}</td>
                                <td>
                                    {{ $item->name }}
                                    <br>
                                    <span title="Status" class="badge bg-info">{{ $item->Status }}</span>
                                </td>
                                <td class="text-right">
                                    <button onclick="approve(`{{ $item->id }}`)" class="btn btn-sm btn-primary"><i class="fas fa-check-circle ico-tab-mini"></i>Mark as Paid</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Approved</span> <strong>Leave Conversion</strong>")
        })

        function approve(id) {
            Swal.fire({
                title: "Confirm Approve",
                text : 'Approve this leave credit conversion?',
                showCancelButton: true,
                confirmButtonText: "Yes",
                confirmButtonColor : '{{ env("SUCCESS") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('leaveConversions.approve') }}",
                        type : "GET",
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Leave conversion approved!'
                            })

                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'An error occurred while trying to approve leave conversion!'
                            })
                        }
                    })
                }
            });
        }
    </script>
@endpush
