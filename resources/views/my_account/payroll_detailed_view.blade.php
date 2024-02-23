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
        <div class="col-lg-12">
            <a href="{{ route('users.payroll-dashboard') }}" class="btn btn-link-muted" style="margin-right: 30px; display: inline;"><i class="fas fa-arrow-left"></i></a>
            <p class="text-md" style="display: inline-block;">Payroll Dashboard</p>
        </div>

        <div class="col-lg-12 mt-4">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="text-md">Payslip Detailed View</p>
                        </div>
                        {{-- form --}}
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
    
                        {{-- table --}}
                        <div class="col-lg-12 mt-3 table-responsive">
                            <table class="table table-hover" id="payslips-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="min-width: 160px;">Payroll Term</th>
                                        <th rowspan="2" class="text-center">Total Hours Rendered</th>
                                        <th rowspan="2" class="text-center">Total Working Hours</th>
                                        <th rowspan="2" class="text-center">Monthly Wage</th>
                                        <th rowspan="2" class="text-center">Term Wage</th>
                                        <th colspan="2" class="text-center">Overtime</th>
                                        <th colspan="2" class="text-center">Abs/Late/UT</th>
                                        <th rowspan="2"  class="text-center">Longevity</th>
                                        <th rowspan="2"  class="text-center">Rice/Laundry</th>
                                        <th colspan="2"  class="text-center">Other Salary Adj.</th>
                                        <th rowspan="2"  class="text-center">TOTAL AMOUNT</th>
                                        <th rowspan="2"  class="text-center">MC Loan</th>
                                        <th colspan="2"  class="text-center">Pag-Ibig</th>
                                        <th colspan="2"  class="text-center">SSS</th>
                                        <th rowspan="2"  class="text-center">PhilHealth Cont.</th>
                                        <th rowspan="2"  class="text-center">AR Others</th>
                                        <th rowspan="2"  class="text-center">Salary Only WT</th>
                                        <th rowspan="2"  class="text-center">Total Tax Wheld</th>
                                        <th rowspan="2"  class="text-center">Total Deductions</th>
                                        <th rowspan="2"  class="text-center">Net Pay</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Hours</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Hours</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Addons</th>
                                        <th class="text-center">Deductions</th>
                                        <th class="text-center">Cont.</th>
                                        <th class="text-center">Loan</th>
                                        <th class="text-center">Cont.</th>
                                        <th class="text-center">Loan</th>
                                    </tr>
                                </thead>
                                <tbody>
    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-1">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="text-md">Graphical View</p>
                        </div>
                        <div class="col-lg-12" id="graph-holder">
                            <canvas id="payslip-canvas" height="350" style="height: 350px;"></canvas>
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
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')

            getPayrollData($('#years').val())

            $('#years').on('change', function() {
                getPayrollData(this.value)
            })
        })

        function getPayrollData(year) {
            $('#payslip-canvas').remove()
            $('#graph-holder p').remove()
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

                    if (isNull(res)) {
                        $('#graph-holder').append(`<p class='text-center'>No data found</p>`)
                    } else {
                        $('#graph-holder').append(`<canvas id="payslip-canvas" height="350" style="height: 350px;"></canvas>`)
                        populatePaylipsTable(res)
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

        function populatePaylipsTable(res) {
            var TotalHoursRendered = 0
            var TotalWorkedHours = 0
            var MonthlyWage = 0
            var TermWage = 0
            var OvertimeHours = 0
            var OvertimeAmount = 0
            var AbsentHours = 0
            var AbsentAmount = 0
            var Longevity = 0
            var RiceLaundry = 0
            var OtherSalaryAdditions = 0
            var OtherSalaryDeductions = 0
            var TotalPartialAmount = 0
            var MotorycleLoan = 0
            var PagIbigContribution = 0
            var PagIbigLoan = 0
            var SSSContribution = 0
            var SSSLoan = 0
            var PhilHealthContribution = 0
            var OtherDeductions = 0
            var SalaryWithholdingTax = 0
            var TotalWithholdingTax = 0
            var TotalDeductions = 0
            var NetPay = 0
            $.each(res, function(index, el) {
                $('#payslips-table tbody').append(`
                    <tr>
                        <td>` + moment(res[index]['SalaryPeriod']).format('MMMM DD, YYYY') + `</td>
                        <td class='text-right'>` + (dashZero(res[index]['TotalHoursRendered'])) + `</td>
                        <td class='text-right'>` + (dashZero(res[index]['TotalWorkedHours'])) + `</td>
                        <td class='text-right'>` + (dashZero(res[index]['MonthlyWage'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['TermWage'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['OvertimeHours'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['OvertimeAmount'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['AbsentHours'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['AbsentAmount'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['Longevity'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['RiceLaundry'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['OtherSalaryAdditions'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['OtherSalaryDeductions'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['TotalPartialAmount'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['MotorycleLoan'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['PagIbigContribution'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['PagIbigLoan'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['SSSContribution'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['SSSLoan'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['PhilHealthContribution'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['OtherDeductions'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['SalaryWithholdingTax'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['TotalWithholdingTax'])) + `</td>
                        <td class="text-right">` + (dashZero(res[index]['TotalDeductions'])) + `</td>
                        <td class="text-right"><strong>` + (dashZero(res[index]['NetPay'])) + `</strong></td>
                    </tr>
                `)

                TotalHoursRendered += parseFloat(res[index]['TotalHoursRendered'])
                TotalWorkedHours += parseFloat(res[index]['TotalWorkedHours'])
                MonthlyWage += parseFloat(res[index]['MonthlyWage'])
                TermWage += parseFloat(res[index]['TermWage'])
                OvertimeHours += parseFloat(res[index]['OvertimeHours'])
                OvertimeAmount += parseFloat(res[index]['OvertimeAmount'])
                AbsentHours += parseFloat(res[index]['AbsentHours'])
                AbsentAmount += parseFloat(res[index]['AbsentAmount'])
                Longevity += parseFloat(res[index]['Longevity'])
                RiceLaundry += parseFloat(res[index]['RiceLaundry'])
                OtherSalaryAdditions += parseFloat(res[index]['OtherSalaryAdditions'])
                OtherSalaryDeductions += parseFloat(res[index]['OtherSalaryDeductions'])
                TotalPartialAmount += parseFloat(res[index]['TotalPartialAmount'])
                MotorycleLoan += parseFloat(res[index]['MotorycleLoan'])
                PagIbigContribution += parseFloat(res[index]['PagIbigContribution'])
                PagIbigLoan += parseFloat(res[index]['PagIbigLoan'])
                SSSContribution += parseFloat(res[index]['SSSContribution'])
                SSSLoan += parseFloat(res[index]['SSSLoan'])
                PhilHealthContribution += parseFloat(res[index]['PhilHealthContribution'])
                OtherDeductions += parseFloat(res[index]['OtherDeductions'])
                SalaryWithholdingTax += parseFloat(res[index]['SalaryWithholdingTax'])
                TotalWithholdingTax += parseFloat(res[index]['TotalWithholdingTax'])
                TotalDeductions += parseFloat(res[index]['TotalDeductions'])
                NetPay += parseFloat(res[index]['NetPay'])
            })

            $('#payslips-table tbody').append(`
                <tr>
                    <th><strong>TOTAL</strong></th>
                    <th class='text-right'>` + (toMoney(TotalHoursRendered)) + `</th>
                    <th class='text-right'>` + (toMoney(TotalWorkedHours)) + `</th>
                    <th class='text-right'>` + (toMoney(MonthlyWage)) + `</th>
                    <th class="text-right">` + (toMoney(TermWage)) + `</th>
                    <th class="text-right">` + (toMoney(OvertimeHours)) + `</th>
                    <th class="text-right">` + (toMoney(OvertimeAmount)) + `</th>
                    <th class="text-right">` + (toMoney(AbsentHours)) + `</th>
                    <th class="text-right">` + (toMoney(AbsentAmount)) + `</th>
                    <th class="text-right">` + (toMoney(Longevity)) + `</th>
                    <th class="text-right">` + (toMoney(RiceLaundry)) + `</th>
                    <th class="text-right">` + (toMoney(OtherSalaryAdditions)) + `</th>
                    <th class="text-right">` + (toMoney(OtherSalaryDeductions)) + `</th>
                    <th class="text-right">` + (toMoney(TotalPartialAmount)) + `</th>
                    <th class="text-right">` + (toMoney(MotorycleLoan)) + `</th>
                    <th class="text-right">` + (toMoney(PagIbigContribution)) + `</th>
                    <th class="text-right">` + (toMoney(PagIbigLoan)) + `</th>
                    <th class="text-right">` + (toMoney(SSSContribution)) + `</th>
                    <th class="text-right">` + (toMoney(SSSLoan)) + `</th>
                    <th class="text-right">` + (toMoney(PhilHealthContribution)) + `</th>
                    <th class="text-right">` + (toMoney(OtherDeductions)) + `</th>
                    <th class="text-right">` + (toMoney(SalaryWithholdingTax)) + `</th>
                    <th class="text-right">` + (toMoney(TotalWithholdingTax)) + `</th>
                    <th class="text-right">` + (toMoney(TotalDeductions)) + `</th>
                    <th class="text-right"><strong>` + (toMoney(NetPay)) + `</strong></th>
                </tr>
            `)

            loadPayslipGraph(res)
        }

        function loadPayslipGraph(tblData) {
            var payslipCanvas = document.getElementById('payslip-canvas').getContext('2d')
            var ticksStyle = { fontColor : '{{ $colorProf != null ? "#fff" : "#495057" }}', fontStyle:'bold'}

            var labels = []
            var netPay = []
            var deductions = []
            var subTotal = []
            for(let i=0; i<tblData.length; i++) {
                labels[i] = moment(tblData[i].SalaryPeriod).format("MMM-DD") + 'th'
                netPay[i] = tblData[i].NetPay
                deductions[i] = tblData[i].TotalDeductions
                subTotal[i] = tblData[i].TotalPartialAmount
            }

            var chartData = {
                labels: labels,
                datasets: [
                    {
                        label: 'Gross Pay',
                        backgroundColor: 'rgba(210, 214, 222, 0)',
                        borderColor: '#adadad',
                        pointRadius: 3,
                        pointColor: '#adadad',
                        pointStrokeColor: '#adadad',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: subTotal
                    },
                    {
                        label: 'Deductions',
                        backgroundColor: 'rgba(210, 214, 222, 0)',
                        borderColor: '{{ env("DANGER") }}',
                        pointRadius: 3,
                        pointColor: '{{ env("DANGER") }}',
                        pointStrokeColor: '{{ env("DANGER") }}',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: deductions
                    },
                    {
                        label: 'Net Pay',
                        backgroundColor: 'rgba(60,141,188,0)',
                        borderColor: '{{ env("SUCCESS") }}',
                        pointRadius: 4,
                        pointColor: '{{ env("SUCCESS") }}',
                        pointStrokeColor: 'r{{ env("SUCCESS") }}',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'r{{ env("SUCCESS") }}',
                        data: netPay
                    },
                ]
            }

            var chartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: true,
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
    </script>
@endpush