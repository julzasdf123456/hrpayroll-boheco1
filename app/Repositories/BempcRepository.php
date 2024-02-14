<?php

namespace App\Repositories;

use App\Models\Bempc;
use App\Repositories\BaseRepository;

class BempcRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'DeductionFor',
        'EmployeeId',
        'DeductionSchedule',
        'Amount',
        'UserId',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Bempc::class;
    }
}
