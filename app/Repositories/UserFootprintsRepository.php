<?php

namespace App\Repositories;

use App\Models\UserFootprints;
use App\Repositories\BaseRepository;

class UserFootprintsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'UserId',
        'LogName',
        'LogDetails',
        'ComputerName'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return UserFootprints::class;
    }
}
