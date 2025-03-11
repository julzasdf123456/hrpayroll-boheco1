@php
    use App\Models\Permission;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>{{ $header ?? "Technical Difficulties Page." }}</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">        
        {{-- LEAVE FORM --}}
        <div class="col">          
            {{-- LEAVE FORM --}} 
            <div class="card shadow-none">
                <div class="card-body">
                    <span class="text-muted">This feature is on halt due to technical issues and difficulties.</span>
                    <span class="text-muted">Please bear with us for a moment.</span><br/><br/>
                    <span class="text-muted">Kindly refer to the manual filing for leave submissions. Thank you.</span>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>

    </div>
@endsection