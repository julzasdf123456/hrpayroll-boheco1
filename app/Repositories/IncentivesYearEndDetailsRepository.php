<?php

namespace App\Repositories;

use App\Models\IncentivesYearEndDetails;
use App\Repositories\BaseRepository;

class IncentivesYearEndDetailsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'IncentivesId',
        'EmployeeId',
        'FourteenthMonthPay',
        'ThirteenthMonthDifferential',
        'CashGift',
        'VacationLeave',
        'SickLeave',
        'LoyaltyAward',
        'SubTotal',
        'AROthers',
        'BEMPC',
        'WithholdingTaxes',
        'NetPay',
        'UserId',
        'EmployeeType',
        'Department'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return IncentivesYearEndDetails::class;
    }
}
