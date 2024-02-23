@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;
@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('users.payroll-dashboard') }}" class="btn btn-link-muted" style="margin-right: 30px;"><i class="fas fa-arrow-left"></i></a>
            <p class="text-md" style="display: inline-flex;">Payroll Dashboard</p>

            <div id="app" class="mt-2">
                <attach-boheco-account></attach-boheco-account>
            </div>
            @vite('resources/js/app.js')
        </div>
    </div>

</div>
@endsection

@push('page_scripts')
    
@endpush