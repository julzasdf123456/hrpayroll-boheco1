@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Positions</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('positions.create') }}">
                        Add New
                    </a>

                    <a href="{{ route('positions.tree-view') }}" class="btn btn-primary-skinny float-right ico-tab-mini">Tree View</a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card shadow-none">
            <div class="card-header">
                <div class="card-tools">
                    <form action="{{ route('positions.index') }}" method="GET">
                        <select name="Department" class="form-control form-control-sm" style="width: 150px; display: inline;">
                            <option value="OGM" {{ isset($_GET['Department']) && $_GET['Department']==='OGM' ? 'selected' : '' }}>OGM</option>
                            <option value="ISD" {{ isset($_GET['Department']) && $_GET['Department']==='ISD' ? 'selected' : '' }}>ISD</option>
                            <option value="ESD" {{ isset($_GET['Department']) && $_GET['Department']==='ESD' ? 'selected' : '' }}>ESD</option>
                            <option value="PGD" {{ isset($_GET['Department']) && $_GET['Department']==='PGD' ? 'selected' : '' }}>PGD</option>
                            <option value="OSD" {{ isset($_GET['Department']) && $_GET['Department']==='OSD' ? 'selected' : '' }}>OSD</option>
                            <option value="SEEAD" {{ isset($_GET['Department']) && $_GET['Department']==='SEEAD' ? 'selected' : '' }}>SEEAD</option>
                        </select>

                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @include('positions.table')

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {{ $positions->withQueryString()->links() }}
            </div>
        </div>
    </div>

@endsection

