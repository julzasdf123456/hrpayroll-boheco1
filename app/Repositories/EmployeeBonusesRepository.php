<?php

namespace App\Repositories;

use App\Models\EmployeeBonuses;
use App\Repositories\BaseRepository;

class EmployeeBonusesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'Incentive',
        'IncentiveDescription',
        'BaseAmount',
        'MaxUntaxableAmount',
        'DeductUponReceipt',
        'TaxDeductionAmount',
        'NetAmountPay',
        'DateReleased',
        'Notes',
        'Year'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EmployeeBonuses::class;
    }
}
