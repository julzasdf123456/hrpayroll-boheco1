<?php

namespace App\Repositories;

use App\Models\TaskChecklists;
use App\Repositories\BaseRepository;

class TaskChecklistsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TaskHeadId',
        'Title',
        'Description',
        'TargetDate',
        'DueDate',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TaskChecklists::class;
    }
}
