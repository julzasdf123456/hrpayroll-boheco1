<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollExpandedDetails extends Model
{
    public $table = 'PayrollExpandedDetails';

    public $fillable = [
        'id',
        'EmployeeId',
        'SalaryPeriod',
        'From',
        'To',
        'TotalHoursRendered',
        'TotalWorkedHours',
        'MonthlyWage',
        'TermWage',
        'OvertimeHours',
        'OvertimeAmount',
        'AbsentHours',
        'AbsentAmount',
        'Longevity',
        'RiceLaundry',
        'OtherSalaryAdditions',
        'OtherSalaryDeductions',
        'TotalPartialAmount',
        'MotorycleLoan',
        'PagIbigContribution',
        'PagIbigLoan',
        'SSSContribution',
        'SSSLoan',
        'PhilHealthContribution',
        'OtherDeductions',
        'SalaryWithholdingTax',
        'TotalWithholdingTax',
        'TotalDeductions',
        'NetPay',
        'GeneratedBy',
        'AuditedBy',
        'CheckedBy',
        'ApprovedBy',
        'GeneratedDate',
        'AuditedDate',
        'CheckedDate',
        'ApprovedDate',
        'Status',
        'Notes',
        'Department',
        'EmployeeType',
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'SalaryPeriod' => 'date',
        'From' => 'date',
        'To' => 'date',
        'TotalHoursRendered' => 'decimal:2',
        'TotalWorkedHours' => 'decimal:2',
        'MonthlyWage' => 'decimal:2',
        'TermWage' => 'decimal:2',
        'OvertimeHours' => 'decimal:2',
        'OvertimeAmount' => 'decimal:2',
        'AbsentHours' => 'decimal:2',
        'AbsentAmount' => 'decimal:2',
        'Longevity' => 'decimal:2',
        'RiceLaundry' => 'decimal:2',
        'OtherSalaryAdditions' => 'decimal:2',
        'OtherSalaryDeductions' => 'decimal:2',
        'TotalPartialAmount' => 'decimal:2',
        'MotorycleLoan' => 'decimal:2',
        'PagIbigContribution' => 'decimal:2',
        'PagIbigLoan' => 'decimal:2',
        'SSSContribution' => 'decimal:2',
        'SSSLoan' => 'decimal:2',
        'PhilHealthContribution' => 'decimal:2',
        'OtherDeductions' => 'decimal:2',
        'SalaryWithholdingTax' => 'decimal:2',
        'TotalWithholdingTax' => 'decimal:2',
        'TotalDeductions' => 'decimal:2',
        'NetPay' => 'decimal:2',
        'GeneratedBy' => 'string',
        'AuditedBy' => 'string',
        'CheckedBy' => 'string',
        'ApprovedBy' => 'string',
        'GeneratedDate' => 'datetime',
        'AuditedDate' => 'datetime',
        'CheckedDate' => 'datetime',
        'ApprovedDate' => 'datetime',
        'Status' => 'string',
        'Notes' => 'string',
        'Department' => 'string',
        'EmployeeType' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'SalaryPeriod' => 'nullable',
        'From' => 'nullable',
        'To' => 'nullable',
        'TotalHoursRendered' => 'nullable|numeric',
        'TotalWorkedHours' => 'nullable|numeric',
        'MonthlyWage' => 'nullable|numeric',
        'TermWage' => 'nullable|numeric',
        'OvertimeHours' => 'nullable|numeric',
        'OvertimeAmount' => 'nullable|numeric',
        'AbsentHours' => 'nullable|numeric',
        'AbsentAmount' => 'nullable|numeric',
        'Longevity' => 'nullable|numeric',
        'RiceLaundry' => 'nullable|numeric',
        'OtherSalaryAdditions' => 'nullable|numeric',
        'OtherSalaryDeductions' => 'nullable|numeric',
        'TotalPartialAmount' => 'nullable|numeric',
        'MotorycleLoan' => 'nullable|numeric',
        'PagIbigContribution' => 'nullable|numeric',
        'PagIbigLoan' => 'nullable|numeric',
        'SSSContribution' => 'nullable|numeric',
        'SSSLoan' => 'nullable|numeric',
        'PhilHealthContribution' => 'nullable|numeric',
        'OtherDeductions' => 'nullable|numeric',
        'SalaryWithholdingTax' => 'nullable|numeric',
        'TotalWithholdingTax' => 'nullable|numeric',
        'TotalDeductions' => 'nullable|numeric',
        'NetPay' => 'nullable|numeric',
        'GeneratedBy' => 'nullable|string|max:50',
        'AuditedBy' => 'nullable|string|max:50',
        'CheckedBy' => 'nullable|string|max:50',
        'ApprovedBy' => 'nullable|string|max:50',
        'GeneratedDate' => 'nullable',
        'AuditedDate' => 'nullable',
        'CheckedDate' => 'nullable',
        'ApprovedDate' => 'nullable',
        'Status' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:2000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Department' => 'nullable|string',
        'EmployeeType' => 'nullable|string',
    ];

    
}