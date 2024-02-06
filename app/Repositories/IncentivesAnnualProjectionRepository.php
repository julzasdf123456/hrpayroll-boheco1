<?php

namespace App\Repositories;

use App\Models\IncentivesAnnualProjection;
use App\Repositories\BaseRepository;

class IncentivesAnnualProjectionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Year',
        'Incentive',
        'IncentiveDescription',
        'Amount',
        'IsTaxable',
        'MaxUntaxableAmount'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return IncentivesAnnualProjection::class;
    }
}
