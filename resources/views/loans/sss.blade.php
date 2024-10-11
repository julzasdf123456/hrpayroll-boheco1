@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-6">
                    <h4>
                        SSS Loans
                    </h4>
                </div>

                <div class="col-lg-6">
                    <button onclick="showSSSLoanCreate()" class="btn btn-primary float-right">Create New</button>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <div class="card-title text-muted">Sorted Latest to Oldest <i class="fas fa-sort-amount-up" style="margin-left: 9px;"></i></div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover table-sm">
                        <thead>
                            <th class="text-center">Employee</th>
                            <th class="text-center">Terms</th>
                            <th class="text-center">Payroll Deduction Term</th>
                            <th class="text-center">Monthly Amortization</th>
                            <th class="text-center">Date Created</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td style="cursor: pointer;" onclick="showDetails(`{{ $item->id }}`)">{{ Employees::getMergeNameFormal($item) }}</td>
                                    <td style="cursor: pointer;" onclick="showDetails(`{{ $item->id }}`)">{{ round($item->Terms) . ' (' . $item->TermUnit . ')' }}</td>
                                    <td style="cursor: pointer;" onclick="showDetails(`{{ $item->id }}`)">{{ $item->PaymentTerm }}</td>
                                    <td style="cursor: pointer;" onclick="showDetails(`{{ $item->id }}`)" class="text-right">₱ {{ number_format($item->MonthlyAmmortization, 2) }}</td>
                                    <td style="cursor: pointer;" onclick="showDetails(`{{ $item->id }}`)">{{ date('M d, Y h:i A', strtotime($item->created_at)) }}</td>
                                    <td style="cursor: pointer;">
                                        {!! Form::open(['route' => ['loans.destroy', $item->id], 'method' => 'delete']) !!}
                                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs float-right', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                        {!! Form::close() !!}
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

@include('loans.modal_create_sss_loan')

@include('loans.modal_loan_details')

@push('page_scripts')
    <script>
        function showSSSLoanCreate() {
            $('#modal-sss-loan').modal('show')
        }

        function showDetails(loanId) {
            $('#modal-loan-details').modal('show')
            $('#loan-details-table tbody tr').remove()

            $.ajax({
                url : "{{ route('loans.get-loan-details-ajax') }}",
                type : "GET",
                data : {
                    LoanId : loanId,
                },
                success : function(res) {
                    if (!isNull(res)) {
                        $.each(res, function(index, element) {
                            $('#loan-details-table tbody').append(addDetailsRow(res[index]['Month'], res[index]['MonthlyAmmortization'], res[index]['Paid']))
                        })
                    }
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error', 
                        text : 'Error getting loan details!'
                    })
                }
            })
        }

        function addDetailsRow(month, amount, status) {
            var statusClass = !isNull(status) ? 'fas fa-check-circle text-success' : 'fas fa-exclamation-circle text-warning'
            return `<tr>
                        <td>` + moment(month).format('MMMM DD, YYYY') + `</td>
                        <td class='text-right'>` + toMoney(amount) + `</td>
                        <td><i class='` + statusClass + ` ico-tab-mini'></i>` + (isNull(status) ? 'Unpaid' : status) + `</td>
                    </tr>`
        }
    </script>
@endpush
