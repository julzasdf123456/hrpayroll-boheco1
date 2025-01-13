@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users Details</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('users.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                        </div>
      
                      <h3 class="profile-username text-center">{{ $users->name }}</h3>
      
                      {{-- <p class="text-muted text-center">Software Engineer</p> --}}
      
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ $users->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Following</b> <a class="float-right">543</a>
                            </li>
                            <li class="list-group-item">
                                <b>Friends</b> <a class="float-right">13,287</a>
                            </li>
                        </ul>

                        <a href="{{ route('users.edit', [$users->id]) }}" class="btn btn-primary btn-block"><b>Update</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-none">
                    <div class="card-header border-0">
                        <span class="card-title">Role(s) Assigned</span>

                        <div class="card-tools">
                            <a class="btn btn-tool" href="{{ route('users.assign-roles', [$users->id]) }}" title="Add or Edit Roles"><i class="fas fa-unlock"></i></a>
                            <a href="{{ route('users.add-roles', $users->id) }}" class="btn btn-tool btn-sm" title="Add Permissions"><i class="fas fa-key"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($roles as $key => $item)
                            <span><strong>{{ $item }} {{ $key == count($roles)-1 ? '' : ', ' }}</strong></span>
                        @endforeach

                        <p class="text-muted text-sm mt-4">Permissions</p>

                        <ul class="text-sm">
                            @foreach ($permissions as $item)
                                <li>{{ $item->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
