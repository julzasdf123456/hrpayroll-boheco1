<?php

namespace App\Repositories;

use App\Models\OvertimeSignatories;
use App\Repositories\BaseRepository;

class OvertimeSignatoriesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'OvertimeId',
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
        return OvertimeSignatories::class;
    }
}
