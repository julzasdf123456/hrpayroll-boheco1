<?php

namespace App\Repositories;

use App\Models\Incentives;
use App\Repositories\BaseRepository;

class IncentivesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'IncentiveName',
        'Notes',
        'UserId'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Incentives::class;
    }
}
