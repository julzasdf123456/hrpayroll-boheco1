<?php

namespace App\Repositories;

use App\Models\LeaveUsersForOthers;
use App\Repositories\BaseRepository;

class LeaveUsersForOthersRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'LeaveCreator',
        'EmployeeId',
        'Department',
        'Status',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LeaveUsersForOthers::class;
    }
}
