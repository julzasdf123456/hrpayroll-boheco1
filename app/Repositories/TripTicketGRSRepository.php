<?php

namespace App\Repositories;

use App\Models\TripTicketGRS;
use App\Repositories\BaseRepository;

class TripTicketGRSRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TripTicketId',
        'Purpose',
        'TotalMileage',
        'TotalLiters',
        'TypeOfFuel',
        'CarRatio',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TripTicketGRS::class;
    }
}
