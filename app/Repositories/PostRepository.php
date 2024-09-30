<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\BaseRepository;

class PostRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'PostContent',
        'PostUserId',
        'Priority',
        'RepostOriginalUserId',
        'PostType',
        'RepostOriginalPostId',
        'Privacy',
        'PostRawText'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Post::class;
    }
}
