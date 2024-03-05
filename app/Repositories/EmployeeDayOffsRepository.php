<?php

namespace App\Repositories;

use App\Models\EmployeeDayOffs;
use App\Repositories\BaseRepository;

class EmployeeDayOffsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'DayOff',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EmployeeDayOffs::class;
    }
}
