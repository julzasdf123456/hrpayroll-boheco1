<?php

namespace App\Repositories;

use App\Models\TaskHeads;
use App\Repositories\BaseRepository;

class TaskHeadsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Title',
        'Description',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TaskHeads::class;
    }
}
