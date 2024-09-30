<?php

namespace App\Repositories;

use App\Models\PostComments;
use App\Repositories\BaseRepository;

class PostCommentsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'CommenterUserId',
        'PostId',
        'Comment',
        'Type'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PostComments::class;
    }
}
