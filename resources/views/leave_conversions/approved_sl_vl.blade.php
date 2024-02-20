@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
<div class="content px-3 row">
    <div class="col-lg-8">
        <p class="text-muted">NOTE: If the leave credit conversion requests are to be added in the Year-end incentives, do not mark them as paid. The system will automatically fetch all data to the SL and VL columns of the year-end incentives section.</p>
    </div>
    <div class="col-lg-4">
        <a class="btn btn-primary-skinny float-right" href="{{ route('leaveConversions.print-all') }}"><i class="fas fa-print ico-tab-mini"></i>Print All</a>
        <button onclick="markAllAsDone()" class="btn btn-primary float-right ico-tab-mini" href=""><i class="fas fa-check-circle ico-tab-mini"></i>Mark All as Done</button>
    </div>
    <div class="col-lg-12">
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
                            {{-- <th class="text-center" rowspan="2"></th> --}}
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
                                    <button onclick="markAsDone(`{{ $item->id }}`)" class="btn btn-sm btn-primary"><i class="fas fa-check-circle ico-tab-mini"></i>Mark Done</button>
                                    <a href="{{ route('leaveConversions.print-single', [$item->id]) }}" class="btn btn-sm btn-primary-skinny"><i class="fas fa-print"></i></a>
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

        function markAsDone(id) {
            Swal.fire({
                title: "Mark as Done?",
                text : 'By confirming, you are acknowledging that this leave credit conversion has already been issued a check. Please proceed with caution.',
                showCancelButton: true,
                confirmButtonText: "Mark as Done",
                confirmButtonColor : '{{ env("SUCCESS") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('leaveConversions.mark-as-done') }}",
                        type : "GET",
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Leave conversion marked as completed!'
                            })

                            $('#' + id).remove()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'An error occurred while trying to mark leave conversion!'
                            })
                        }
                    })
                }
            })
        }

        function markAllAsDone() {
            Swal.fire({
                title: "Mark All as Done?",
                text : 'By confirming, you are acknowledging that all leave credit conversion listed below has already been issued a check. Please proceed with caution.',
                showCancelButton: true,
                confirmButtonText: "Mark All as Done",
                confirmButtonColor : '{{ env("SUCCESS") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('leaveConversions.mark-all-as-done') }}",
                        type : "GET",
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'All leave conversion marked as completed!'
                            })

                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'An error occurred while trying to mark all leave conversion!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush
