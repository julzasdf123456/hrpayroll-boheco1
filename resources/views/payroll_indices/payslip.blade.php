@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Your Payslip</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center">MATER DEI COLLEGE</h5>
                    <address class="text-center">Tubigon, Bohol</address>

                    <h5 class="text-center"><strong>PAYSLIP</strong></h5>
                    <br>
                    <span>Employee Name: <strong>{{ Employees::getMergeName($employee) }}</strong></span><br>
                    <span>Employee ID No: <strong>{{ $employee->id }}</strong></span><br>
                    <span>Designation: <strong>{{ $employee->Designation }}</strong></span><br>
                    <span>Salary Period: <strong>{{ date('F d, Y', strtotime($payrollIndex->SalaryPeriod)) }}</strong></span><br>

                    <table class="table table-borderless table-sm">
                        <tr>
                            <th>BASIC</th>
                            <th class="text-right">RATE/LOAD</th>
                            <th class="text-right">NO. OF. LOADS</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px;">Instructional</td>
                            <td class="text-right">{{ number_format($employeeDesignation->SalaryPerLoad, 2) }}</td>
                            <td class="text-right">{{ $employeeDesignation->SubjectLoad }}</td>
                            <th class="text-right">{{ number_format($employeeDesignation->SalaryAmount, 2) }}</th>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px;">Administrative</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <th class="text-right">-</th>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px;">Support Staff/AddOns</td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <th class="text-right">{{ number_format($employeeDesignation->SalaryAddOns, 2) }}</th>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px;">TOTAL MONTHLY PAY</td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <th class="text-right">{{ number_format(floatval($employeeDesignation->SalaryAddOns) + floatval($employeeDesignation->SalaryAmount), 2) }}</th>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px;">Gross Pay</td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <th class="text-right">{{ number_format($payrollDetails->GrossSalary, 2) }}</th>
                        </tr>
                        <tr>
                            <th>DEDUCTIONS</th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th></th>
                        </tr>
                        @foreach ($deductions as $item)
                            <tr>
                                <td style="padding-left: 30px;">{{ $item->Entity }}</td>
                                <td class="text-right"></td>
                                <td class="text-right">{{ number_format($item->ContributionAmount, 2) }}</td>
                                <th class="text-right"></th>
                            </tr>
                        @endforeach
                        <tr>
                            <th>NET PAY</th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right">{{ number_format($payrollDetails->NetSalary, 2) }}</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection