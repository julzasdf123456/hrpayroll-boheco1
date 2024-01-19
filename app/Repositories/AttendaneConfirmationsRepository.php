<?php

namespace App\Repositories;

use App\Models\AttendaneConfirmations;
use App\Repositories\BaseRepository;

class AttendaneConfirmationsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'Reason',
        'AMIn',
        'AMOut',
        'PMIn',
        'PMOut',
        'OTIn',
        'OTOut',
        'Status',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AttendaneConfirmations::class;
    }
}
