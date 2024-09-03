<?php

namespace App\Repositories;

use App\Models\LeaveBalanceMonthlyImage;
use App\Repositories\BaseRepository;

class LeaveBalanceMonthlyImageRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'Vacation',
        'Sick',
        'Special',
        'Maternity',
        'MaternityForSoloMother',
        'Paternity',
        'SoloParent',
        'Notes',
        'Month',
        'Year'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LeaveBalanceMonthlyImage::class;
    }
}
