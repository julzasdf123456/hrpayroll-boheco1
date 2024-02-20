@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div id="app">
            <year-end-bonuses></year-end-bonuses>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Generate</span> <strong>Year-end Incentive/Bonus</strong> <i class='fas fa-gifts ico-tab-left-mini'></i> <i class='fas fa-candy-cane'></i>")
        })
    </script>
@endpush