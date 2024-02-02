<?php

namespace App\Repositories;

use App\Models\LoanDetails;
use App\Repositories\BaseRepository;

class LoanDetailsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'LoanId',
        'Interest',
        'Principal',
        'MonthlyAmmortization',
        'Month',
        'Paid'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LoanDetails::class;
    }
}
