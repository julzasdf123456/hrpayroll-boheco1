@extends('layouts.app')

<meta name="accountNo" content="{{ $accountNo }}">

@section('content')

    {{-- OTHERS --}}
    <div id="app">
        <account-view></account-view>
    </div>
    @vite('resources/js/app.js')
@endsection