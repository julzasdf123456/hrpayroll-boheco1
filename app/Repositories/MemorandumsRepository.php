<?php

namespace App\Repositories;

use App\Models\Memorandums;
use App\Repositories\BaseRepository;

class MemorandumsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'MemoNumber',
        'MemoTitle',
        'MemoContent',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Memorandums::class;
    }
}
