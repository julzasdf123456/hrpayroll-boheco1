<?php

namespace App\Repositories;

use App\Models\TravelOrders;
use App\Repositories\BaseRepository;

class TravelOrdersRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'DateFiled',
        'Destination',
        'Purpose',
        'UserId',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TravelOrders::class;
    }
}
