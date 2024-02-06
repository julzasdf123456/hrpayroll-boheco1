<?php

namespace App\Repositories;

use App\Models\EmployeeIncentiveAnnualProjections;
use App\Repositories\BaseRepository;

class EmployeeIncentiveAnnualProjectionsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'Year',
        'Incentive',
        'IncentiveDescription',
        'Amount',
        'IsTaxable',
        'MaxUntaxableAmount',
        'DeductMonthly'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EmployeeIncentiveAnnualProjections::class;
    }
}
