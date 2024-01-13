<?php

namespace App\Repositories;

use App\Models\TripTicketPassengers;
use App\Repositories\BaseRepository;

class TripTicketPassengersRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TripTicketId',
        'EmployeeId',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TripTicketPassengers::class;
    }
}
