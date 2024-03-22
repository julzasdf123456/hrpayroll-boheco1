@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;

@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12" style="margin-bottom: 26px;">
            <p class="text-center no-pads text-lg">Personal Information</p>
            <p class="text-center no-pads text-muted d-none d-sm-block">Manage and view your personal information</p>
        </div>
    </div>

    {{-- CONTENT LINEAR --}}
    <div class="col-lg-8 offset-lg-2 mt-2">
        <div id="app">
            <personal-info></personal-info>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection

@push('page_scripts')

@endpush