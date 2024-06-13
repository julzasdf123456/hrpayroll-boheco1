@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div id="app">
            <all-travel-orders></all-travel-orders>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Browse </span> <strong>All Travel Orders</strong>")
        })
    </script>
@endpush