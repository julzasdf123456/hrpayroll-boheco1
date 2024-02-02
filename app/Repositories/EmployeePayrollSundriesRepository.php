<?php

namespace App\Repositories;

use App\Models\EmployeePayrollSundries;
use App\Repositories\BaseRepository;

class EmployeePayrollSundriesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'Longevity',
        'RiceAllowance',
        'Insurances',
        'PagIbigContribution',
        'PagIbigLoan',
        'SSSContribution',
        'SSSLoan',
        'PhilHealth',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EmployeePayrollSundries::class;
    }
}
