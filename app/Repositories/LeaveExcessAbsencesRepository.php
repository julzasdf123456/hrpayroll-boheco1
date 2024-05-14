<?php

namespace App\Repositories;

use App\Models\LeaveExcessAbsences;
use App\Repositories\BaseRepository;

class LeaveExcessAbsencesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'EmployeeId',
        'LeaveDate',
        'HoursAbsent'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LeaveExcessAbsences::class;
    }
}
