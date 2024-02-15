<?php

namespace App\Repositories;

use App\Models\IncentiveDetails;
use App\Repositories\BaseRepository;

class IncentiveDetailsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'EmployeeId',
        'IncentivesId',
        'SubTotal',
        'BasicSalary',
        'TermWage',
        'OtherDeductions',
        'BEMPC',
        'NetPay'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return IncentiveDetails::class;
    }
}
