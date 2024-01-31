<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialDutyDays extends Model
{
    public $table = 'SpecialDutyDays';

    public $fillable = [
        'id',
        'Date',
        'Notes',
        'Term'
    ];

    protected $casts = [
        'id' => 'string',
        'Date' => 'string',
        'Notes' => 'string',
        'Term' => 'string'
    ];

    public static array $rules = [
        'Date' => 'nullable',
        'Notes' => 'nullable|string|max:1500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Term' => 'nullable|string'
    ];

    
}
