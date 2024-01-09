@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Add Permissions to {{ $role->name }}</h1>
                </div>
            </div> 
        </div>
    </section>

    <div>
        <form method="POST" class="form-horizontal" action="{{ url('roles/create-role-permissions') }}">
            @csrf
            @foreach ($permissions as $item)
                <div class="form-check">
                    <input type="checkbox" name="item[]" id="{{ $item->id }}" class="form-check-input" value="{{ $item->id }}" @if($role->permissions) @if(in_array($item->id, $role->permissions->pluck('id')->toArray())) checked @endif @endif>
                    {{ Form::label($item->id, $item->name, ['class' => 'form-check-label']) }}
                </div>
            @endforeach
            
            <input type="hidden" name="roleId" value="{{ $role->id }}">

            <button type="submit" class="btn btn-info"><i class="fas fa-fw fa-play"></i> Submit</button>
        </form>
    </div>
@endsection