@extends('layouts.app')
    
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div id="app">
                <leave-balances></leave-balances>
            </div>
            @vite('resources/js/app.js')
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'></span> <strong>All Leave Balances</strong>")
        })

    </script>
@endpush

