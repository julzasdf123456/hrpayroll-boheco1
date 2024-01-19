<?php

namespace App\Repositories;

use App\Models\AttendaneConfirmationSignatories;
use App\Repositories\BaseRepository;

class AttendaneConfirmationSignatoriesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'AttendanceConfirmationId',
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
        return AttendaneConfirmationSignatories::class;
    }
}
