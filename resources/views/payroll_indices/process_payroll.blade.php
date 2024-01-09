@php
    use App\Models\Employees;
    use App\Models\PayrollIndex;

    set_time_limit(720);

    // INITALIZE NUMBER OF DAYS
    $noOfDays = abs(round((strtotime($payrollIndex->DateTo . ' +1 day') - strtotime($payrollIndex->DateFrom)) / 86400));
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Process Payroll</h4>
                </div>
            </div>
        </div>
    </section>
    <a href="{{ route('payrollIndices.generate-payroll', [$payrollIndex->id]) }}" class="btn btn-success" style="margin-bottom: 20px;">Generate</a>
    
    <div class="row">
        <div class="col-lg-1">
            <span><strong>Legend:</strong></span>
        </div>
        <div class="col-lg-2">
            <p style="border-left: 25px solid #ffc107; padding-left: 20px;">Late/Undertimed</p>
        </div>
        <div class="col-lg-2">
            <p style="border-left: 25px solid #dc3545; padding-left: 20px;">Absent</p>
        </div>
        <div class="col-lg-2">
            <p style="border-left: 25px solid #28a745; padding-left: 20px;">On Leave</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @php
                // dd($data);
            @endphp
            <table class="table table-responsive table-sm table-hover table-col-color table-bordered">
                <thead>
                    {{-- HEADERS --}}
                    <tr>
                        <th rowspan="2" style="text-align: center; width: 250px;">Employees({{ $payrollIndex->EmployeeType }})</th>
                        @for ($i = 0; $i < $noOfDays; $i++)
                            <th colspan="3" style="{{ $i%2 ? 'background-color: #DDDDDD;' : '' }}" class="{{ PayrollIndex::colorSaturday(date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'))) }} {{ PayrollIndex::colorSunday(date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'))) }}">
                                {{ date('Y-m-d', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days')) }}
                                <br>
                                {{ date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days')) }}</th>
                        @endfor
                        <th rowspan="2" class="text-center">Base Salary</th>
                        <th rowspan="2" class="text-center">Gross (Base/22/8)</th>
                        <th rowspan="2" class="text-center">Total Minutes<br>Late/Absent/UT</th>
                        <th rowspan="2" class="text-center">Total Deduction<br>Late/Absent/UT</th>
                        <th rowspan="2" class="text-center" style="background-color: #ffebee;">SSS</th>
                        <th rowspan="2" class="text-center" style="background-color: #ffebee;">PhilHealth</th>
                        <th rowspan="2" class="text-center" style="background-color: #ffebee;">Pag-Ibig</th>
                        <th rowspan="2" class="text-center" style="background-color: #ffebee;">Total Deductions</th>
                        <th rowspan="2" class="text-center" style="background-color: #e0f7fa;">Add-Ons</th>
                        <th rowspan="2" class="text-center" style="background-color: #f3e5f5;">Sub-Total</th>
                        <th rowspan="2" class="text-center" style="background-color: #ffebee;">Tax</th>
                        <th rowspan="2" class="text-center" style="background-color: #b2dfdb;">Net Salary</th>
                    </tr>
                    <tr>
                        @for ($i = 0; $i < $noOfDays; $i++)
                            <th class="{{ PayrollIndex::colorSaturday(date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'))) }} {{ PayrollIndex::colorSunday(date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'))) }}" style="{{ $i%2 ? 'background-color: #DDDDDD;' : '' }}">AM</th>
                            <th class="{{ PayrollIndex::colorSaturday(date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'))) }} {{ PayrollIndex::colorSunday(date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'))) }}" style="{{ $i%2 ? 'background-color: #DDDDDD;' : '' }}">PM</th>
                            <th class="{{ PayrollIndex::colorSaturday(date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'))) }} {{ PayrollIndex::colorSunday(date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'))) }}" style="{{ $i%2 ? 'background-color: #DDDDDD;' : '' }}">OT</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        @php
                            $grossAmt = 0.0;
                        @endphp
                        <tr>
                            {{-- SHOW EMPLOYEES IN THIS CATEGORY --}}
                            <td>{{ $item['Employee'] }}</td>
                            {{-- FETCH NUMBER OF DAYS BASED ON DateFrom and DateTo --}}
                            @for ($i = 0; $i < $noOfDays; $i++)
                                @php
                                    $day = date('D', strtotime($payrollIndex->DateFrom . ' +' . $i . ' days'));
                                @endphp
                                <th title="{{ $item['Data'][$i]['AMMinutesLate'] > 0 && $item['Data'][$i]['AMMinutesLate'] < 240 ? $item['Data'][$i]['AMMinutesLate'] . ' Minutes Late' : '' }}" class="text-center {{ $item['Data'][$i]['AMLeave']!=null ? 'payroll-color-leave' : PayrollIndex::colorizeAttendance($item['Data'][$i]['AM']) }} {{ PayrollIndex::colorSaturday($day) }} {{ PayrollIndex::colorSunday($day) }} {{ PayrollIndex::isAbsent($item['Data'][$i]['AM'], $day) ? 'payroll-color-absent' : '' }}" style="{{ $i%2 ? 'background-color: #DDDDDD;' : '' }}">{{ $item['Data'][$i]['AM']==0 ? '-' : $item['Data'][$i]['AM'] }}</td>
                                <th title="{{ $item['Data'][$i]['PMMinutesLate'] > 0 && $item['Data'][$i]['PMMinutesLate'] < 240 ? $item['Data'][$i]['PMMinutesLate'] . ' Minutes Late' : '' }}" class="text-center {{ $item['Data'][$i]['PMLeave']!=null ? 'payroll-color-leave' : PayrollIndex::colorizeAttendance($item['Data'][$i]['PM']) }} {{ PayrollIndex::colorSaturday($day) }} {{ PayrollIndex::colorSunday($day) }}  {{ PayrollIndex::isAbsent($item['Data'][$i]['PM'], $day) ? 'payroll-color-absent' : '' }}" style="{{ $i%2 ? 'background-color: #DDDDDD;' : '' }}">{{ $item['Data'][$i]['PM']==0 ? '-' : $item['Data'][$i]['PM'] }}</td>
                                <th class="text-center text-muted {{ PayrollIndex::colorSaturday($day) }} {{ PayrollIndex::colorSunday($day) }} " style="{{ $i%2 ? 'background-color: #DDDDDD;' : '' }}">{{ $item['Data'][$i]['OT']==0 ? '-' : $item['Data'][$i]['OT'] }}</td>
                                @php
                                    $grossAmt += floatval($item['Data'][$i]['AMTotal']) + floatval($item['Data'][$i]['PMTotal']) + floatval($item['Data'][$i]['OTTotal']);
                                @endphp
                            @endfor
                            <td class="text-right">{{ number_format($item['BaseSalary'], 2) }}</td>
                            <th class="text-right" title="Salary/Hour: {{ number_format($item['SalaryPerHour'], 2) }}">{{ number_format($grossAmt, 2) }}</th>       
                            <td class="text-right">{{ number_format($item['TotalMinutesLate']) }} mins <br> ({{ number_format(floatval($item['TotalMinutesLate'])/60, 2) }} hrs)</td>  
                            <td class="text-right text-danger">{{ number_format($item['TotalLateDeduction'], 2) }}</td>                     
                            <td class="text-right" style="background-color: #ffebee;">{{ number_format($item['SSS'], 2) }}</td>
                            <td class="text-right" style="background-color: #ffebee;">{{ number_format($item['PhilHealth'], 2) }}</td>
                            <td class="text-right" style="background-color: #ffebee;">{{ number_format($item['Pag-Ibig'], 2) }}</td>                            
                            <th class="text-right" style="background-color: #ffebee;">{{ number_format($item['TotalDeductions'], 2) }}</th>                    
                            <td class="text-right" style="background-color: #e0f7fa;">{{ number_format($item['SalaryAddOns'], 2) }}</td>                   
                            <th class="text-right" style="background-color: #f3e5f5;">{{ number_format(($grossAmt-$item['TotalDeductions'])+$item['SalaryAddOns'], 2) }}</th> 
                            <td class="text-right" style="background-color: #ffebee;">{{ number_format($item['Tax'], 2) }}</td>  
                            <th class="text-right" style="background-color: #b2dfdb;">{{ number_format((($grossAmt-$item['TotalDeductions'])+$item['SalaryAddOns'])-$item['Tax'], 2) }}</th>  
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection