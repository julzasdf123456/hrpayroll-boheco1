@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <h4 style="margin-top: 10px; margin-left: 10px;">Create Deductions (AR - Others)</h4>
    </div>

    <div class="col-lg-12">
        <div id="app">
            <multiple-payroll-deductions></multiple-payroll-deductions>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
        })
    </script>
@endpush