<?php

namespace App\Repositories;

use App\Models\OtherAddonsDeductions;
use App\Repositories\BaseRepository;

class OtherAddonsDeductionsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'DeductionName',
        'DeductionDescription',
        'ScheduleDate',
        'Notes',
        'Status',
        'DeductionAmount',
        'Type',
        'AddonName',
        'AddonAmount'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return OtherAddonsDeductions::class;
    }
}
