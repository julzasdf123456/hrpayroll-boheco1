@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>My Dash</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- TESTS --}}
        <div class="col-lg-12">

        </div>

        {{-- LEAVE --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title"><i class="fas fa-calendar-day ico-tab-mini"> </i> My Leave Applications<i class="text-muted" style="font-size: .8em; margin-left: 10px;">Latest 5 leave</i></span>
                    <div class="card-tools">
                        <a href="{{ route('employees.show', [Auth::user()->employee_id]) }}" title="View All Leave" class="btn btn-tool"><i class="fas fa-external-link-alt"></i></a>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-borderless table-sm">
                        <thead>
                            <th width="20%;">Date Filed</th>
                            <th width="50%;">Content</th>
                            <th width="5%"></th>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $item)
                                <tr>
                                    <td><a href="{{ route('leaveApplications.show', [$item->id]) }}">{{ date('F d, Y', strtotime($item->created_at)) }}</a></td>
                                    <td><p class="ellipsize-dynamic">{{ $item->Content }}</p></td>
                                    <td>
                                        @if ($item->Status != 'APPROVED')
                                            <i class="fas fa-info-circle text-warning"></i>
                                        @else
                                            <i class="fas fa-check-circle text-success"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- INFO --}}

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab-mini"> </i> Informations<i class="text-muted" style="font-size: .8em; margin-left: 10px;">Latest 5 info</i></span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-borderless">
                        <thead>
                            <th width="92%;"></th>
                            <th width="3%"></th>
                        </thead>
                        <tbody>
                            @foreach ($info as $item)
                                <tr>
                                    <td>{{ $item->Content }}</td>
                                    <td>
                                        <a href="{{ route('notifications.show', [$item->id]) }}"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
