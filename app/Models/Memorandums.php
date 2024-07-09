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
        'MemoType'
    ];

    protected $casts = [
        'id' => 'string',
        'MemoNumber' => 'string',
        'MemoTitle' => 'string',
        'MemoContent' => 'string',
        'Status' => 'string',
        'MemoType' => 'string',
    ];

    public static array $rules = [
        'MemoNumber' => 'nullable|string|max:50',
        'MemoTitle' => 'nullable|string|max:1000',
        'MemoContent' => 'nullable|string|max:16',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'MemoType' => 'nullable|string',
    ];

    
}
