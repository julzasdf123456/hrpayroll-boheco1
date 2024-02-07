<?php

namespace App\Repositories;

use App\Models\PayrollExpandedDetails;
use App\Repositories\BaseRepository;

class PayrollExpandedDetailsRepository extends BaseRepository
{
    protected $fieldSearchable = [
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
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PayrollExpandedDetails::class;
    }
}
