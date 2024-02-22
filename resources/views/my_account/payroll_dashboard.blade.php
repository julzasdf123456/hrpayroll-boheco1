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
            <p class="text-center no-pads text-muted">Manage and view your payroll activities</p>
        </div>
    </div>

    {{-- CONTENT LINEAR --}}
    <div class="col-lg-8 offset-lg-2">
        {{-- Payslips and payroll data --}}
        <div class="section">
            <div class="row">
                <div class="col-10">
                    <p class="no-pads text-md">Your payroll data and payslips</p>
                    <p class="no-pads text-muted">List of your annual/monthly payroll history data and payslips. Also contained here is the summary of your payroll so you can efficiently make analytics and projections on your future expenses.</p>
                </div>
                <div class="col-2 center-contents">
                    <img style="width: 100% !important;" class="img-fluid" src="{{ asset('imgs/payslips.png') }}" alt="User profile picture">
                </div>
            </div>

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

                        <div class="col-lg-12 mt-3">
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
                    <div class="dropdown float-right">
                        <a class="btn btn-primary-skinny dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                          More Options
                        </a>
                      
                        <div class="dropdown-menu">
                            <button class="dropdown-item" data-toggle="modal" data-target="#modal-leave-logs">Leave credit logs</button>
                            <button class="dropdown-item" data-toggle="modal" data-target="#modal-leave-conversion-logs">Leave conversion logs</button>
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

            $('#years').on('change', function() {
                getPayrollData(this.value)
            })
        })

        function getPayrollData(year) {
            $('#payslips-loader').removeClass('gone')
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
                        <td class='text-right'>` + (firstTerm === 0 ? '-' :  '₱ ' + toMoney(firstTerm)) + `</td>
                        <td class='text-right'>` + (secondTerm === 0 ? '-' : '₱ ' + toMoney(secondTerm)) + `</td>
                        <td class='text-right'>` + (total === 0 ? '-' : '₱ ' + toMoney(total)) + `</td>
                    </tr>
                `)

                firstTermTotal += firstTerm
                secondTermTotal += secondTerm
                overallTotal += total
            })
            $('#payslips-table tbody').append(`
                <tr>
                    <th>TOTAL EARNINGS</th>
                    <th class='text-right'>` + (firstTermTotal === 0 ? '-' :  '₱ ' + toMoney(firstTermTotal)) + `</th>
                    <th class='text-right'>` + (secondTermTotal === 0 ? '-' : '₱ ' + toMoney(secondTermTotal)) + `</th>
                    <th class='text-right'>` + (overallTotal === 0 ? '-' : '₱ ' + toMoney(overallTotal)) + `</th>
                </tr>
            `)
        }
    </script>
@endpush