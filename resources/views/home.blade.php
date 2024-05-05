@extends('layouts.app')

@section('content')
<div class="content">
    @include('employee_finder')
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>HR Admin </span> <strong>Dashboard</strong>")
        })
    </script>
@endpush
