@php
    use App\Models\Employees;
    use App\Models\Users;
@endphp
@extends('layouts.app')

@section('content')
    @include('leave_applications.leave_view')
@endsection
