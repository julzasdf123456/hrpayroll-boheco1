@php
    use App\Models\Employees;
    use App\Models\Users;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Leave Application Approvals</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title">Preview</span>
                </div>
                <div class="card-body">
                    <h4 class="text-center px-0 mx-0"><strong>Mater Dei College</strong></h4>
                    <p class="text-center">Cabulijan, Tubigon, Bohol</p>
                    
                    <br>
                    <p><strong>Employee: </strong> {{ Employees::getMergeName(Employees::find(Users::where('employee_id', $leaveApplication->EmployeeId)->first()->employee_id)) }}</p>
                    <span>
                        <strong>Date Filed: </strong>{{ date('F d, Y', strtotime($leaveApplication->created_at)) }}
                        <br>
                        <strong>Days of Leave: </strong>{{ date('F d, Y', strtotime($leaveApplication->DateFrom)) }}{{ $leaveApplication->DateTo==null ? '' : (' to ' . date('F d, Y', strtotime($leaveApplication->DateTo))) }} ({{ $leaveApplication->NumberOfDays . ' days' }})
                    </span>
                    <br>
                    <p><strong>Purpose: </strong> {{ $leaveApplication->Content }}</p>
                    <br>
                    <div class="row justify-content-around">
                        @foreach ($leaveSignatories as $item)
                            @if (Auth::id() == $item->EmployeeId)
                                <div class="col-5" style="margin-top: 20px;">
                                    <p class="text-center" style="padding: 0 !important; margin: 0 !important;">
                                        <u><strong>{{ Employees::getMergeName($item) }}</strong></u>   
                                    </p>
                                    <address class="text-center"><i>{{ $item->Designation }}</i></address>
                                    <p class="text-center">
                                        @if ($item->Status == 'APPROVED')

                                        @else
                                            <a href="{{ route('leaveApplications.approve-leave', [$leaveApplication->id, $item->id]) }}" class="btn btn-xs btn-primary text-center"><i class="fas fa-check-circle ico-tab-mini"></i>Approve This Leave</a>
                                        @endif
                                    </p>                                  
                                </div>  
                            @else
                                <div class="col-5" style="margin-top: 20px;">
                                    <p class="text-center text-muted" style="padding: 0 !important; margin: 0 !important;">
                                        <u><strong>{{ Employees::getMergeName($item) }}</strong></u>
                                        
                                    </p>
                                    <address class="text-center text-muted"><i>{{ $item->Designation }}</i></address>
                                    @if ($item->Status == 'APPROVED')
                                        <p class="text-center"><button class="btn btn-xs btn-default text-center"><i class="fas fa-check-circle ico-tab-mini"></i>Approved</button></p>
                                    @else
                                        <p class="text-center"><button class="btn btn-xs btn-default text-center"><i class="fas fa-info-circle ico-tab-mini"></i>Unapproved</button></p>
                                    @endif
                                </div>                               
                            @endif
                            
                        @endforeach
                        
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('home') }}" class="btn btn-primary float-right">Finish</a>
                </div>
            </div>
        </div>
    </div>
@endsection