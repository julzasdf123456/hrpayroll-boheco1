<?php

namespace App\Repositories;

use App\Models\TravelOrderSignatories;
use App\Repositories\BaseRepository;

class TravelOrderSignatoriesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TravelOrderId',
        'UserId',
        'Rank',
        'Status',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TravelOrderSignatories::class;
    }
}
