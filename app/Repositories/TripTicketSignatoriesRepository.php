<?php

namespace App\Repositories;

use App\Models\TripTicketSignatories;
use App\Repositories\BaseRepository;

class TripTicketSignatoriesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TripTicketId',
        'EmployeeId',
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
        return TripTicketSignatories::class;
    }
}
