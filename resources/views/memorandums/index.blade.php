@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Memorandums</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                        href="{{ route('memorandums.create-memo') }}">
                        Create New Memo
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div id="app">
                <memo-index></memo-index>
            </div>
            @vite('resources/js/app.js')
        </div>
    </div>

@endsection
