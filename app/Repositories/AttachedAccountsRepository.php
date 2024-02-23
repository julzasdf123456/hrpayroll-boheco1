<?php

namespace App\Repositories;

use App\Models\AttachedAccounts;
use App\Repositories\BaseRepository;

class AttachedAccountsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'AccountNumber',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AttachedAccounts::class;
    }
}
