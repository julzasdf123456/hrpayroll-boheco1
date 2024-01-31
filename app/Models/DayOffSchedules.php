<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayOffSchedules extends Model
{
    public $table = 'DayOffSchedules';

    public $fillable = [
        'id',
        'Days',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'Days' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'Days' => 'nullable|string|max:300',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
