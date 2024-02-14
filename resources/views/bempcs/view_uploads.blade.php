@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-8">
                <h4><span class="text-muted">BEMPC Deductions for </span>{{ $for }} ({{ date('Y', strtotime($date)) }})</h4>
                <span class="text-muted">Uploaded on {{ date('F d, Y', strtotime($date)) }}</span>
            </div>
            <div class="col-lg-4">
                <button onclick="remove(`{{ $for }}`, `{{ $date }}`)" class="btn btn-danger float-right"><i class="fas fa-trash ico-tab-mini"></i>Delete Data</button>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12 table-responsive">
        <table class="table table-sm table-hover table-bordered">
            <thead>
                <th class="text-center">Employee ID</th>
                <th class="text-center">Employee</th>
                <th class="text-center">Deduction Schedule<br>(if Payroll)</th>
                <th class="text-center">Deduction Amount</th>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->EmployeeId }}</td>
                        <td>{{ Employees::getMergeNameFormal($item) }}</td>
                        <td>{{ $item->DeductionSchedule != null ? date('F d, Y', strtotime($item->DeductionSchedule)) : '-' }}</td>
                        <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        function remove(deductionfor, date) {
            Swal.fire({
                title: "Confirm Delete?",
                text : 'Deleting this will remove the deductions. This cannot be undone.',
                showCancelButton: true,
                confirmButtonText: "Proceed Delete",
                confirmButtonColor : "#e0321b",
                denyButtonText: `Cancel`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('bempcs.delete') }}",
                        type : "GET",
                        data : {
                            For : deductionfor,
                            Date : date
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Data deleted!'
                            })
                            window.location.href = "{{ route('bempcs.index') }}"
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error deleting data!'
                            })
                        }
                    })
                } 
            });
        }
    </script>
@endpush
