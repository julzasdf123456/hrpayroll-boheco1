@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;

    $years = [];
    for($i=0; $i<24; $i++) {
        $years[$i] = date('Y', strtotime('today -' . $i . ' years'));
    }
@endphp
@extends('layouts.app')

@section('content')
<meta name="employee-id-current" content="{{ Auth::user()->employee_id }}">
<div class="content">
    {{-- <div class="row">
        <div class="col-lg-12" style="margin-bottom: 26px;">
            <p class="text-center no-pads text-lg">Attendance Management</p>
            <p class="text-center no-pads text-muted">View and monitor your daily attendance logs.</p>
        </div>
    </div> --}}

    {{-- CONTENT LINEAR --}}
    <div class="col-lg-8 offset-lg-2">
        <a href="{{ route('users.personal-info') }}" class="btn btn-link-muted" style="margin-right: 30px; display: inline;"><i class="fas fa-arrow-left"></i></a>
        <p class="text-md" style="display: inline-block;">Dependents</p>


        {{-- OTHERS --}}
        <div id="app">
            <add-dependents></add-dependents>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection
