<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComments extends Model
{
    public $table = 'PostComments';

    public $fillable = [
        'id',
        'CommenterUserId',
        'PostId',
        'Comment',
        'Type', // COMMENT, REPLY
    ];

    protected $casts = [
        'id' => 'string',
        'CommenterUserId' => 'string',
        'PostId' => 'string',
        'Comment' => 'string',
        'Type' => 'string'
    ];

    public static array $rules = [
        'CommenterUserId' => 'nullable|string|max:50',
        'PostId' => 'nullable|string|max:90',
        'Comment' => 'nullable|string|max:16',
        'Type' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
