<?php

namespace App\Repositories;

use App\Models\OtherPayrollDeductions;
use App\Repositories\BaseRepository;

class OtherPayrollDeductionsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'DeductionName',
        'DeductionDescription',
        'ScheduleDate',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return OtherPayrollDeductions::class;
    }
}
