<?php

namespace App\Repositories;

use App\Models\OffsetSignatories;
use App\Repositories\BaseRepository;

class OffsetSignatoriesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'OffsetBatchId',
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
        return OffsetSignatories::class;
    }
}
