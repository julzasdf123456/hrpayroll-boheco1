@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Upcoming Payroll Deductions</h4>
                </div>
                <div class="col-lg-6">
                    <button onclick="showOtherDeductions()" class="btn btn-primary float-right">Create New Deduction</button>
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
