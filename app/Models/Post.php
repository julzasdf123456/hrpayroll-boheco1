<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $table = 'Posts';

    public $fillable = [
        'id',
        'PostContent',
        'PostUserId',
        'Priority',
        'RepostOriginalUserId',
        'PostType',
        'RepostOriginalPostId',
        'Privacy',
        'PostRawText',
        'ReactionCount',
    ];

    protected $casts = [
        'id' => 'string',
        'PostContent' => 'string',
        'PostUserId' => 'string',
        'RepostOriginalUserId' => 'string',
        'PostType' => 'string',
        'RepostOriginalPostId' => 'string',
        'Privacy' => 'string',
        'PostRawText' => 'string',
        'ReactionCount' => 'string',
    ];

    public static array $rules = [
        'PostContent' => 'nullable|string',
        'PostUserId' => 'nullable|string|max:50',
        'Priority' => 'nullable',
        'RepostOriginalUserId' => 'nullable|string|max:50',
        'PostType' => 'nullable|string|max:60',
        'RepostOriginalPostId' => 'nullable|string|max:90',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Privacy' => 'nullable|string|max:50',
        'PostRawText' => 'nullable|string',
        'ReactionCount' => 'nullable|string',
    ];

    public static function path() {
        return public_path() . "/posts/";
    }
}
