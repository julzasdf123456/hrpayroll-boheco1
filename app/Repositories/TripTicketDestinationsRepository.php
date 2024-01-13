<?php

namespace App\Repositories;

use App\Models\TripTicketDestinations;
use App\Repositories\BaseRepository;

class TripTicketDestinationsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TripTicketId',
        'DestinationAddress',
        'BarangayId',
        'TownId',
        'EmployeeId',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TripTicketDestinations::class;
    }
}
