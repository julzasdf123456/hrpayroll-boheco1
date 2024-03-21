<?php

namespace App\Repositories;

use App\Models\TravelOrderEmployees;
use App\Repositories\BaseRepository;

class TravelOrderEmployeesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TravelOrderId',
        'EmployeeId',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TravelOrderEmployees::class;
    }
}
