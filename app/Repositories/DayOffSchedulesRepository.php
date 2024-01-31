<?php

namespace App\Repositories;

use App\Models\DayOffSchedules;
use App\Repositories\BaseRepository;

class DayOffSchedulesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Days',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DayOffSchedules::class;
    }
}
