<?php

namespace App\Repositories;

use App\Models\LeaveConversions;
use App\Repositories\BaseRepository;

class LeaveConversionsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'VacationDays',
        'SickDays',
        'VacationAmount',
        'SickAmount',
        'Year',
        'Status',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LeaveConversions::class;
    }
}
