{{-- @extends('layouts.app')

@section('content')
<div class="row">
    <div class='col-lg-12 col-md-12'>
        <br>
        <h4 class="text-center display-5">Search Employees</h4>
        <br>
        <div class="row">
            <!-- SEARCH BAR -->
            <div class="col-md-8 offset-md-2">
                <div class="input-group">
                    <input type="search" id='searchparam' class="form-control" placeholder="Type Name or ID">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default" id="searchBtn">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEARCH RESULTS -->
        <div id="search-results" class="table-responsive container" style="margin-top: 20px;">
            <table class="table table-borderless table-hover" id="results">
                <thead>

                </thead>
                <tbody>

                </tbody>
            </table>     
        </div>
    </div>        
</div>
@endsection

@push('page_scripts')
    <script>
        function getResults(params) {
            $.ajax({
                url : '/employees/get-search-results',
                type : 'GET',
                data : {
                    params : params
                },
                success : function(res) {
                    $('#results tbody tr').remove();
                    $('#results tbody').append(res);
                   
                },
                error : function(err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function() {
            getResults('')

            $('#searchparam').on('keyup', function() {
                getResults(this.value)
            })
        })
    </script>
@endpush
 --}}

@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div id="app">
            <employees-search></employees-search>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection
