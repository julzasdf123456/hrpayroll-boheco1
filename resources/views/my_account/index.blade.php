@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;
@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    {{-- IMG --}}
    <div class="center-contents">
        <div style="display: block; margin-bottom: 18px;">
            <img style="width: 112px !important;" class="profile-user-img img-fluid img-circle" src="{{ asset('imgs/prof-img.png') }}" alt="User profile picture">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12" style="margin-bottom: 18px;">
            <p class="text-center no-pads text-lg">{{ Employees::getMergeName($employee) }}</p>
            <p class="text-center no-pads text-muted">{{ $employee->Position }}</p>
        </div>

        <div class="col-lg-12">
            <div class="row">
                {{-- LEAVE --}}
                <div class="col-lg-4 offset-lg-2 col-md-12">
                    <div class="card shadow-none" style="height: 100%;">
                        <div class="card-body">
                            <h4>Leave Credits</h4>
                            <span class="text-muted">Your leave credits overview</span>

                            <table class="table table-sm" style="margin-top: 18px;">
                                <tbody>
                                    <tr>
                                        <td class="text-muted" style="vertical-align: middle;">Vacation leave credit</td>
                                        <td class="text-right">
                                            <span class="text-primary text-lg">{{ $leaveBalances != null ? number_format($leaveBalances->Vacation, 1) : '0' }}</span>
                                            <span class="text-muted"> days</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted" style="vertical-align: middle;">Sick leave credit</td>
                                        <td class="text-right">
                                            <span class="text-primary text-lg">{{ $leaveBalances != null ? number_format($leaveBalances->Sick, 1) : '0' }}</span>
                                            <span class="text-muted"> days</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="text-center no-pads" style="padding-top: 10px;">
                                <span class="text-primary"><strong>{{ $leaveBalances != null ? number_format($leaveBalances->Special, 1) : '0' }}</strong></span> <span class="text-muted">Special</span> | 
                                <span class="text-primary"><strong>{{ $leaveBalances != null ? number_format($leaveBalances->SoloParent, 1) : '0' }}</strong></span> <span class="text-muted">Solo Parent</span> | 
                                @if ($employee->Gender == 'Male')
                                    <span class="text-primary"><strong>{{ $leaveBalances != null ? number_format($leaveBalances->Paternity, 1) : '0' }}</strong></span> <span class="text-muted">Paternity</span>
                                @else
                                    <span class="text-primary"><strong>{{ $leaveBalances != null ? number_format($leaveBalances->Maternity, 1) : '0' }}</strong></span> <span class="text-muted">Maternity</span> | 
                                    <span class="text-primary"><strong>{{ $leaveBalances != null ? number_format($leaveBalances->MaternityForSoloMother, 1) : '0' }}</strong></span> <span class="text-muted">Solo Mom</span>
                                @endif
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('users.leave-credits', [$employee->id]) }}" class="btn btn-link">Manage your leave credits</a>
                        </div>
                    </div>
                </div>

                {{-- Payroll --}}
                <div class="col-lg-4 col-md-12">
                    <div class="card shadow-none" style="height: 100%;">
                        <div class="card-body">
                            <h4>Attendance & Payroll</h4>
                            <div class="row">
                                <div class="col-7">
                                    <span class="text-muted">View and manage your attendance and payroll information, including all your incentives, overtime pays, deductions, and withholding taxes</span>
                                </div>
                                <div class="col-5 center-contents">
                                    <img style="width: 90% !important;" class="img-fluid" src="{{ asset('imgs/payroll-dash.png') }}" alt="User profile picture">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('users.payroll-dashboard') }}" class="btn btn-link">Go to Payroll dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12" style="margin-top: 12px;">
            <div class="row">
                {{-- TRIPS --}}
                <div class="col-lg-4 offset-lg-2 col-md-12">
                    <div class="card shadow-none" style="height: 100%;">
                        <div class="card-body">
                            <h4>Travels and Trips</h4>
                            
                            <div class="row">
                                <div class="col-8">
                                    <span class="text-muted">View your official {{ env('APP_COMPANY_ABRV') }} travel orders and trip tickets</span>
                                </div>
                                <div class="col-4 center-contents">
                                    <img style="width: 90% !important;" class="img-fluid" src="{{ asset('imgs/trips.png') }}" alt="User profile picture">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="" class="btn btn-link">Trips tickets and travel orders</a>
                        </div>
                    </div>
                </div>

                {{-- My Personal Space --}}
                <div class="col-lg-4 col-md-12">
                    <div class="card shadow-none" style="height: 100%;">
                        <div class="card-body">
                            <h4>Your Personal Space</h4>

                            <div class="row">
                                <div class="col-8">
                                    <span class="text-muted">Manage your personal information and files. You can also manage your training certifcations here.</span>
                                </div>
                                <div class="col-4 center-contents">
                                    <img style="width: 90% !important;" class="img-fluid" src="{{ asset('imgs/my-space.png') }}" alt="User profile picture">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="" class="btn btn-link">Manage personal info</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        });

    </script>
@endpush
