@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-12">
        <div id="app">
            <reeve></reeve>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
    
@endsection
