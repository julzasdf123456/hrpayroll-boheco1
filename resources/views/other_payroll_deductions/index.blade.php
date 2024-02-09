@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Upcoming Payroll Deductions (AR - Others)</h4>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('otherPayrollDeductions.multiple-payroll-deductions') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-stream ico-tab-mini"></i>New Deduction</a>
                    {{-- <button onclick="showOtherDeductions()" class="btn btn-sm btn-primary float-right ico-tab-mini"><i class="fas fa-minus ico-tab-mini"></i>Create Single Deduction</button> --}}
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            @include('other_payroll_deductions.table')
        </div>
    </div>

@endsection

@include('other_payroll_deductions.modal_create_deduction')

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function showOtherDeductions() {
            $('#modal-create-deduction').modal('show')
        }
    </script>
@endpush
