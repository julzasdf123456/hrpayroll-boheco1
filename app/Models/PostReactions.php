<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostReactions extends Model
{
    public $table = 'PostReactions';

    public $fillable = [
        'id',
        'PostId',
        'UserId',
        'ReactionType'
    ];

    protected $casts = [
        'id' => 'string',
        'PostId' => 'string',
        'UserId' => 'string',
        'ReactionType' => 'string'
    ];

    public static array $rules = [
        'PostId' => 'nullable|string|max:90',
        'UserId' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ReactionType' => 'nullable|string|max:50'
    ];

    
}
