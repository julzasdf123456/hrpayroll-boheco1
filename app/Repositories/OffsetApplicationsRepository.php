<?php

namespace App\Repositories;

use App\Models\OffsetApplications;
use App\Repositories\BaseRepository;

class OffsetApplicationsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'PreparedBy',
        'DatePrepared',
        'EmployeeId',
        'DateOfDuty',
        'TimeStart',
        'TimeEnd',
        'PurposeOfDuty',
        'DateOfOffset',
        'OffsetReason',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return OffsetApplications::class;
    }
}
