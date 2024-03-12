<?php

namespace App\Repositories;

use App\Models\Dependents;
use App\Repositories\BaseRepository;

class DependentsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'DependentName',
        'Address',
        'Relationship',
        'Birthdate',
        'IsBeneficiary',
        'Occupation',
        'Disability',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Dependents::class;
    }
}
