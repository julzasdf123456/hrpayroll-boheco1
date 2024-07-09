<?php

namespace App\Repositories;

use App\Models\MemorandumEmployees;
use App\Repositories\BaseRepository;

class MemorandumEmployeesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'MemoId',
        'EmployeeId'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MemorandumEmployees::class;
    }
}
