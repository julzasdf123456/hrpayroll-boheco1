@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Set Custom Permissions for {{ $users->name }}</h5>
                </div>
            </div>
        </div>
    </section>
    
    <div>
        <form class="form row" method="POST" action="{{ url('users/create-roles') }}">
            <!-- SUBMIT -->
            <div class="col-md-4 col-lg-4 offset-lg-4">
                
                @csrf
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">Set Custom Permissions</span>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-fw fa-check"></i> Save</button> 
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($permissions as $permission)
                            <div class="form-check">  
                                <input type="checkbox" class="form-check-input" id="{{ $permission->id }}" value="{{ $permission->id }}" name="permissions[]"  @if($users->permissions) @if(in_array($permission->id, $users->permissions->pluck('id')->toArray())) checked @endif @endif>
                                <label for="{{ $permission->id }}" class="form-check-label">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                        
                        <!-- ADD USER ID -->
                        <input type="hidden" name="userId" value="{{ $users->id }}">  
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-primary float-right"><i class="fas fa-fw fa-check"></i> Save</button> 
                    </div>
                </div>             
            
            </div>
        </form>
    </div>
@endsection