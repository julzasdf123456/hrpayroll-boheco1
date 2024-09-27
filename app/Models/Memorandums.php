<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memorandums extends Model
{
    public $table = 'Memorandums';

    public $fillable = [
        'id',
        'MemoNumber',
        'MemoTitle',
        'MemoContent',
        'Status',
        'MemoType',
        'MemoRawText',
        'UserId'
    ];

    protected $casts = [
        'id' => 'string',
        'MemoNumber' => 'string',
        'MemoTitle' => 'string',
        'MemoContent' => 'string',
        'Status' => 'string',
        'MemoType' => 'string',
        'MemoRawText' => 'string',
        'UserId' => 'string',
    ];

    public static array $rules = [
        'MemoNumber' => 'nullable|string|max:50',
        'MemoTitle' => 'nullable|string',
        'MemoContent' => 'nullable|string',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'MemoType' => 'nullable|string',
        'MemoRawText' => 'nullable|string',
        'UserId' => 'nullable|string',
    ];

    
}
