<?php

namespace App\Repositories;

use App\Models\TravelOrderDays;
use App\Repositories\BaseRepository;

class TravelOrderDaysRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TravelOrderId',
        'Day',
        'Longevity'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TravelOrderDays::class;
    }
}
