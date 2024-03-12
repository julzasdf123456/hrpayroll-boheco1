@php
    use App\Models\Employees;

    $colorProf = Auth::user()->ColorProfile;

    $years = [];
    for($i=0; $i<24; $i++) {
        $years[$i] = date('Y', strtotime('today -' . $i . ' years'));
    }
@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12" style="margin-bottom: 26px;">
            <p class="text-center no-pads text-lg">Payroll Dashboard</p>
            <p class="text-center no-pads text-muted">Manage and view your monthly payroll and incentive activities</p>
        </div>
    </div>

    {{-- CONTENT LINEAR --}}
    <div class="col-lg-8 offset-lg-2">
        {{-- Payslips and payroll data --}}
        <div class="section">
            <div class="row">
                <div class="col-10 relative">
                    <div class="botom-left-contents px-3">
                        <p class="no-pads text-md">Your monthly payroll data and payslips</p>
                        <p class="no-pads text-muted">List of your annual/monthly payroll history data and payslips. Also contained here is the summary 
                            of your payroll so you can efficiently make analytics and projections on your future expenses.</p>
                    </div>
                </div>
                <div class="col-2 center-contents">
                    <img style="width: 100% !important;" class="img-fluid" src="{{ asset('imgs/payslips.png') }}" alt="User profile picture">
                </div>
            </div>

            {{-- payslip summary table --}}
            <div class="card shadow-none mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="text-md">Payslip Summary</p>
                        </div>
                        <div class="col-lg-3 col-md-6 mt-2">
                            <span class="text-muted">Choose Year</span>
                            <select id="years" class="form-control">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-9 col-md-6">
                            <div id="payslips-loader" class="spinner-border text-primary float-right gone" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3 table-responsive">
                            <table class="table table-hover" id="payslips-table">
                                <thead>
                                    <th>Month</th>
                                    <th class="text-right">15th Payroll</th>
                                    <th class="text-right">30th Payroll</th>
                                    <th class="text-right">Total Earnings</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('users.payroll-detailed-view') }}" class="btn btn-primary">Detailed View <i class="fas fa-external-link-alt ico-tab-left-mini"></i></a>

                    <div class="dropdown float-right">
                        <a class="btn btn-primary-skinny dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                          More Options
                        </a>
                      
                        <div class="dropdown-menu">
                            <a href="{{ route('users.attach-boheco-account') }}" class="dropdown-item">Attached BOHECO I Accounts</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- payslip summary graph --}}
            <div class="card shadow-none mt-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="text-md">Graphical View</p>
                        </div>
                        <div class="col-lg-12" id="graph-holder">
                            <canvas id="payslip-canvas" height="300" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="app">
            <withholding-taxes-view></withholding-taxes-view>

            <overtime></overtime>
        </div>
        @vite('resources/js/app.js')

        {{-- incentives --}}
        <div class="section mt-2">
            <div class="row">
                <div class="col-10 relative">
                    <div class="botom-left-contents px-3">
                        <p class="no-pads text-md">Your annual incentives summary</p>
                        <p class="no-pads text-muted">Repository of your annual incentives, bonuses, and benefits.</p>
                    </div>
                </div>
                <div class="col-2 center-contents">
                    <img style="width: 100% !important;" class="img-fluid" src="{{ asset('imgs/incentives.png') }}" alt="User profile picture">
                </div>
            </div>

            {{-- payslip summary table --}}
            <div class="card shadow-none mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="text-md">Incentives Summary</p>
                        </div>
                        <div class="col-lg-3 col-md-6 mt-2">
                            <span class="text-muted">Choose Year</span>
                            <select id="incentives-years" class="form-control">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-9 col-md-6">
                            <div id="incentives-loader" class="spinner-border text-primary float-right gone" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3 table-responsive">
                            <table class="table table-hover" id="incentives-table">
                                <thead>
                                    <th>Incentive</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-right">Deductions</th>
                                    <th class="text-right">Net Pay</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        var months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ]

        $(document).ready(function() {
            getPayrollData($('#years').val())
            getIncentivesData($('#incentives-years').val())

            $('#years').on('change', function() {
                getPayrollData(this.value)
            })

            $('#incentives-years').on('change', function() {
                getIncentivesData(this.value)
            })
        })

        function getPayrollData(year) {
            $('#payslip-canvas').remove()
            $('#payslips-loader').removeClass('gone')
            $('#graph-holder p').remove()
            $('#payslips-table tbody tr').remove()
            $.ajax({
                url : "{{ route('payrollIndices.get-payroll-monthly-data') }}",
                type : "GET",
                data : {
                    Year : year,
                    EmployeeId : "{{ Auth::user()->employee_id }}"
                },
                success : function(res) {
                    $('#payslips-loader').addClass('gone')

                    if (isNull(res)) {
                        $('#graph-holder').append(`<p class='text-center'>No data found</p>`)
                    } else {
                        $('#graph-holder').append(`<canvas id="payslip-canvas" height="300" style="height: 300px;"></canvas>`)
                        var tblData = []

                        for (let i=0; i<months.length; i++) {
                            var mo = null
                            var term15 = 0
                            var term30 = 0
                            $.each(res, function(index, el) {
                                if (moment(res[index]['SalaryPeriod']).format("MMMM") === months[i]) {
                                    if (isNull(mo)) {
                                        mo = months[i]
                                    }
                                    
                                    if (moment(res[index]['SalaryPeriod']).format('DD') === '15') {
                                        term15 = res[index]['NetPay']
                                    } else {
                                        term30 = res[index]['NetPay']
                                    }
                                }
                            })
                            
                            if (!isNull(mo)) {
                                tblData.push({
                                    'Month' : mo,
                                    'FirstTerm' : term15,
                                    'SecondTerm' : term30
                                })
                            }
                        }

                        populatePaylipsTable(tblData)
                    }
                },
                error : function(err) {
                    $('#payslips-loader').addClass('gone')
                    Toast.fire({
                        icon : 'error',
                        text : 'Error fetching payslip data'
                    })
                }
            })
        }

        function populatePaylipsTable(tblData) {
            var firstTermTotal = 0
            var secondTermTotal = 0
            var overallTotal = 0
            $.each(tblData, function(index, el) {
                var firstTerm = parseFloat(tblData[index]['FirstTerm'])
                var secondTerm = parseFloat(tblData[index]['SecondTerm'])
                var total = firstTerm + secondTerm
                $('#payslips-table tbody').append(`
                    <tr>
                        <td>` + tblData[index]['Month'] + `</td>
                        <td class='text-right'>` + (firstTerm === 0 ? '-' :  '₱' + toMoney(firstTerm)) + `</td>
                        <td class='text-right'>` + (secondTerm === 0 ? '-' : '₱' + toMoney(secondTerm)) + `</td>
                        <td class='text-right'>` + (total === 0 ? '-' : '₱' + toMoney(total)) + `</td>
                    </tr>
                `)

                firstTermTotal += firstTerm
                secondTermTotal += secondTerm
                overallTotal += total
            })
            $('#payslips-table tbody').append(`
                <tr>
                    <th>TOTAL EARNINGS</th>
                    <th class='text-right'>` + (firstTermTotal === 0 ? '-' :  '₱' + toMoney(firstTermTotal)) + `</th>
                    <th class='text-right'>` + (secondTermTotal === 0 ? '-' : '₱' + toMoney(secondTermTotal)) + `</th>
                    <th class='text-right'>` + (overallTotal === 0 ? '-' : '₱' + toMoney(overallTotal)) + `</th>
                </tr>
            `)

            loadPayslipGraph(tblData)
        }

        function loadPayslipGraph(tblData) {
            var payslipCanvas = document.getElementById('payslip-canvas').getContext('2d')
            var ticksStyle = { fontColor : '{{ $colorProf != null ? "#fff" : "#495057" }}', fontStyle:'bold'}

            var labels = []
            var firstTerms = []
            var secondTerms = []
            for(let i=0; i<tblData.length; i++) {
                labels[i] = tblData[i].Month
                firstTerms[i] = tblData[i].FirstTerm
                secondTerms[i] = tblData[i].SecondTerm
            }

            var chartData = {
                labels: labels,
                datasets: [
                    {
                        label: '15th',
                        backgroundColor: 'rgba(60,141,188,0)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: 3,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: firstTerms
                    },
                    {
                        label: '30th',
                        backgroundColor: 'rgba(210, 214, 222, 0)',
                        borderColor: '{{ env("SUCCESS") }}',
                        pointRadius: 3,
                        pointColor: '{{ env("SUCCESS") }}',
                        pointStrokeColor: '{{ env("SUCCESS") }}',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: secondTerms
                    }
                ]
            }

            var chartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: true
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks : ticksStyle,
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks : $.extend({
                            beginAtZero:true,
                            callback : function(value) { 
                                if(value>=1000) { 
                                    value/=1000
                                    value+='k'
                                }
                                return '$'+value
                            }}, ticksStyle
                        )
                    }]
                }
            }

            var payslipChart = new Chart(payslipCanvas, { // lgtm[js/unused-local-variable]
                type: 'line',
                data: chartData,
                options: chartOptions
            })
        }

        function getIncentivesData(year) {
            $('#incentives-loader').removeClass('gone')
            $('#incentives-table tbody tr').remove()
            $.ajax({
                url : "{{ route('users.get-incentives-by-employee-id') }}",
                type : "GET",
                data : {
                    Year : year,
                    EmployeeId : "{{ Auth::user()->employee_id }}"
                },
                success : function(res){
                    $('#incentives-loader').addClass('gone')

                    if (!isNull(res)) {
                        populateIncentivesTable(res['incentives'], res['yearEndIncentives'])
                    }
                },
                error : function(err) {
                    $('#incentives-loader').addClass('gone')
                    Toast.fire({
                        icon : 'error',
                        text : 'Error fetching payslip data'
                    })
                }
            })
        }

        function populateIncentivesTable(incentives, yearEnd) {
            var size = incentives.length
            var overAllSubTotal = 0
            var overAllDeductions = 0
            var overallTotal = 0
            for (let i=0; i<size; i++) {
                var deductions = parseFloat(incentives[i]['OtherDeductions']) + parseFloat(incentives[i]['BEMPC'])
                overallTotal +=  parseFloat(incentives[i]['NetPay'])
                overAllDeductions += deductions
                overAllSubTotal += parseFloat(incentives[i]['SubTotal'])
                $('#incentives-table tbody').append(`
                    <tr>
                        <td>` + incentives[i]['IncentiveName'] + `</td>
                        <td class='text-right'>` + (isNull(incentives[i]['SubTotal']) ? '-' : toMoney(parseFloat(incentives[i]['SubTotal']))) + `</td>
                        <td class='text-right'>` + toMoney(deductions) + `</td>
                        <td class='text-right'><strong>` + (isNull(incentives[i]['NetPay']) ? '-' : toMoney(parseFloat(incentives[i]['NetPay']))) + `</strong></td>
                    </tr>
                `)
            }

            // YEAR END
            var yearEndDeductions = 0
            if (!isNull(yearEnd)) {
                var bempc = parseFloat(yearEnd['BEMPC'])
                var arOthers = parseFloat(yearEnd['AROthers'])
                yearEndDeductions = bempc + arOthers

                overAllSubTotal += parseFloat(yearEnd['SubTotal'])
                overAllSubTotal += yearEndDeductions
                overallTotal += parseFloat(yearEnd['NetPay'])
                $('#incentives-table tbody').append(`
                    <tr>
                        <td>` + yearEnd['IncentiveName'] + `</td>
                        <td class='text-right'>` + (isNull(yearEnd['SubTotal']) ? '-' : toMoney(parseFloat(yearEnd['SubTotal']))) + `</td>
                        <td class='text-right'>` + toMoney(yearEndDeductions) + `</td>
                        <td class='text-right'><strong>` + (isNull(yearEnd['NetPay']) ? '-' : toMoney(parseFloat(yearEnd['NetPay']))) + `</strong></td>
                    </tr>
                `)
            }

            // TOTAL
            $('#incentives-table tbody').append(`
                <tr>
                    <th>TOTAL</th>
                    <th class='text-right'>` + toMoney(overAllSubTotal) + `</th>
                    <th class='text-right'>` + toMoney(overAllDeductions) + `</th>
                    <th class='text-right'><strong>` + toMoney(overallTotal) + `</strong></th>
                </tr>
            `)
        }
    </script>
@endpush