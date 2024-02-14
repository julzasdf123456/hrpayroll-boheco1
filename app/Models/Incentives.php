<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incentives extends Model
{
    public $table = 'Incentives';

    public $fillable = [
        'id',
        'IncentiveName',
        'Notes',
        'UserId',
        'Year',
        'Status',
    ];

    protected $casts = [
        'id' => 'string',
        'IncentiveName' => 'string',
        'Notes' => 'string',
        'UserId' => 'string',
        'Year' => 'string',
        'Status' => 'string',
    ];

    public static array $rules = [
        'IncentiveName' => 'nullable|string|max:300',
        'Notes' => 'nullable|string|max:1000',
        'UserId' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Year' => 'nullable|string',
        'Status' => 'nullable|string',
    ];

    
}
