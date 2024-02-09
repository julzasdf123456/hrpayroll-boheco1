@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <h4 style="margin-top: 10px; margin-left: 10px;">Salary-Related Addons and Deductions</h4>
    </div>

    <div class="col-lg-12">
        <div id="app">
            <addons-and-deductions></addons-and-deductions>
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