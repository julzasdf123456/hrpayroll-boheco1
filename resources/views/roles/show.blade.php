@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Role Details</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('roles.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-header">
                <span class="card-title"></span>

                <div class="card-tools">
                    <a href="{{ url('roles/add-permissions/' . $role->id) }}" title="Edit Permissions to this role"><i class="fas fa-key"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @include('roles.show_fields')
                </div>
            </div>

        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Permissions allowed for {{ $role->name }}</span>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                    </tr>
                    @php
                        $permissions = $role->permissions;
                    @endphp
                    @foreach ($permissions as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
