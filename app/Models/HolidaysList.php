<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HolidaysList extends Model
{
    public $table = 'HolidaysList';

    public $fillable = [
        'id',
        'HolidayDate',
        'Holiday',
        'MemoNumber',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'HolidayDate' => 'date',
        'Holiday' => 'string',
        'MemoNumber' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'HolidayDate' => 'nullable',
        'Holiday' => 'nullable|string|max:50',
        'MemoNumber' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
