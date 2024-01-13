<?php

namespace App\Repositories;

use App\Models\TripTickets;
use App\Repositories\BaseRepository;

class TripTicketsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'DatetimeFiled',
        'EmployeeId',
        'PurposeOfTravel',
        'Driver',
        'Status',
        'DatetimeDeparted',
        'DatetimeArrived'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TripTickets::class;
    }
}
