<?php

namespace App\Repositories;

use App\Models\SpecialDutyDays;
use App\Repositories\BaseRepository;

class SpecialDutyDaysRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Date',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SpecialDutyDays::class;
    }
}
