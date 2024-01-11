<?php

namespace App\Repositories;

use App\Models\HolidaysList;
use App\Repositories\BaseRepository;

class HolidaysListRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'HolidayDate',
        'Holiday',
        'MemoNumber',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return HolidaysList::class;
    }
}
