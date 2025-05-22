@php
use Carbon\Carbon;
$months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

$currentMonth = Carbon::now()->format('M');
$currentYear = Carbon::now()->format('Y');
@endphp

@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div id="app">
            {{-- <leave-balances></leave-balances> --}}
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-lg-12" id="balances-filters">

                            <button id="print_balances" class="btn btn-default btn-sm float-right ml-1" title="Print Balances Data"><i class="fas fa-print"></i></button>
                            <input type="number" value="{{ $currentYear }}" min="1970" max="{{ $currentYear }}" maxlength="4" class="form-control form-control-sm float-right ml-1" style="width: 80px;" id="year">
                            <select class="form-control form-control-sm float-right ml-2" style="width: 160px;" id="month">
                                @foreach ( $months as $month )
                                <option value="{{ $month }}" {{ $month === $currentMonth? 'selected': '' }}>{{ $month }}</option>
                                @endforeach
                            </select>

                            <!-- department select -->
                            <select class="form-control form-control-sm float-right" style="width: 160px;" id="department">
                                <option value="All" selected>All</option>
                                <option value="ESD">ESD</option>
                                <option value="ISD">ISD</option>
                                <option value="OGM">OGM</option>
                                <option value="OSD">OSD</option>
                                <option value="PGD">PGD</option>
                                <option value="SEEAD">SEEAD</option>
                                <option value="SUB-OFFICE">SUB-OFFICE</option>
                            </select>
                        </div>

                        <!-- RESULTS -->
                        <div class="col-lg-12 mt-4">
                            <div class="card shadow-none">
                                <div class="card-header">
                                    <span class="card-title">Employees List</span>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-sm table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" rowspan="2">Employees</th>
                                                <th class="text-center" colspan="3">Vacation</th>
                                                <th class="text-center" colspan="3">Sick</th>
                                                <th class="text-center" rowspan="2">Special</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Days</th>
                                                <th class="text-center">Hours</th>
                                                <th class="text-center">Mins</th>
                                                <th class="text-center">Days</th>
                                                <th class="text-center">Hours</th>
                                                <th class="text-center">Mins</th>
                                            </tr>
                                        </thead>
                                        <tbody id="balances-data">
                                            <!-- Data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="right-bottom" style="bottom: 0px !important;">
                <p id="msg-display" class="msg-display shadow" style="font-size: .8em;"><i class="fas fa-check-circle ico-tab-mini text-success"></i>saved!</p>
            </div>

        </div>
        {{-- @vite('resources/js/app.js') --}}
    </div>
</div>

@push('page_scripts')

<script>
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse')
        $('#page-title').html("<span class='text-muted'></span> <strong>All Leave Balances</strong>")

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        loadBalancesData();

        $('#balances-filters').on('change', 'input, select', function() {
            loadBalancesData();
        });

        $('#print_balances').on('click', function() {
            printBalancesData();
        })

        function printBalancesData() {
            var year = $('#year').val();
            var month = $('#month').val();
            var department = $('#department').val();

            // open('/hr/public/leave_balances/print-balances/' + department + '/' + month + '/' + year, '_blank')

            // Use Blade to generate the route URL with placeholders
            var url = "{{ route('leaveBalances.print-balances', ['dept' => ':department', 'month' => ':month', 'year' => ':year']) }}";

            // Replace the placeholders with dynamic values from JavaScript
            url = url.replace(':department', department)
                .replace(':month', month)
                .replace(':year', year);

            // Open the URL in a new tab
            window.open(url, '_blank');
        }

        function loadBalancesData() {
            var year = $('#year').val();
            var month = $('#month').val();
            var department = $('#department').val();

            $.ajax({
                url: "get-merge-data?Department=" + department + "&Month=" + month + "&Year=" + year,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var html = '';
                    $.each(data, function(key, item) {
                        html += '<tr>';
                        html += '<td>' + item.LastName + ", " + item.FirstName + " " + (item.MiddleName ? item.MiddleName : '') + '</td>'; // Replace with actual data
                        html += '<td class="text-right"><strong>' + getDays(item.Vacation) + '</strong> <span class="text-muted text-sm"> days</span></td>';
                        html += '<td class="text-right"><strong>' + getHours(item.Vacation) + '</strong> <span class="text-muted text-sm"> hrs</span></td>';
                        html += '<td class="text-right"><strong>' + getMins(item.Vacation) + '</strong> <span class="text-muted text-sm">mins</span></td>';
                        html += '<td class="text-right"><strong>' + getDays(item.Sick) + '</strong> <span class="text-muted text-sm"> days</span></td>';
                        html += '<td class="text-right"><strong>' + getHours(item.Sick) + '</strong> <span class="text-muted text-sm"> hrs</span></td>';
                        html += '<td class="text-right"><strong>' + getMins(item.Sick) + '</strong> <span class="text-muted text-sm">mins</span></td>';
                        html += '<td class="text-right"><strong>' + Math.round(item.Special) + '</strong> <span class="text-muted text-sm"> days</span></td>';
                        html += '</tr>';
                    });
                    $('#balances-data').html(html);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", status, error);
                    $('#balances-data').html('<tr><td colspan="8" class="text-center">Error loading data.</td></tr>');
                }
            });
        }

        // Initial load (optional)
        // loadBalancesData(); 
    });

    function getDays(mins) {
        return Math.floor(mins / 8 / 60);
    }

    function getHours(mins) {
        mins = parseFloat(mins);
        const days = Math.floor(mins / 8 / 60);
        const exactDaysInMins = days * 8 * 60;
        const excessMins = mins - exactDaysInMins;
        const hours = excessMins / 60;
        return Math.round(hours);
    }

    function getMins(mins) {
        mins = parseFloat(mins);
        const days = Math.floor(mins / 8 / 60);
        const exactDaysInMins = days * 8 * 60;
        const excessMins = mins - exactDaysInMins;
        const hours = Math.floor(excessMins / 60);
        const totalMins = exactDaysInMins + (hours * 60);
        return mins - totalMins;
    }
</script>
@endpush
@endsection