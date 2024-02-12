<?php

namespace App\Repositories;

use App\Models\EmployeeIncntvsProjectionTaxMark;
use App\Repositories\BaseRepository;

class EmployeeIncntvsProjectionTaxMarkRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'Incentive',
        'SalaryPeriod',
        'Deducted'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EmployeeIncntvsProjectionTaxMark::class;
    }
}
