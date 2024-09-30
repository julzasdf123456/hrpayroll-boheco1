<?php

namespace App\Repositories;

use App\Models\PostReactions;
use App\Repositories\BaseRepository;

class PostReactionsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'PostId',
        'UserId',
        'ReactionType'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PostReactions::class;
    }
}
