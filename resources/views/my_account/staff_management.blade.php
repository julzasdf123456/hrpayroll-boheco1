@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;

@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12" style="margin-bottom: 26px;">
            <p class="text-center no-pads text-lg">Staff & Subordinates Mangement</p>
            <p class="text-center no-pads text-muted">Manage your subordinate's tasks, day-offs, and more.</p>
        </div>
    </div>

    {{-- CONTENT LINEAR --}}
    <div class="col-lg-8 offset-lg-2">
        <div id="app">
            <staff-management></staff-management>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection

@push('page_scripts')

@endpush