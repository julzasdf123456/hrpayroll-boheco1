<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelOrderSignatories extends Model
{
    public $table = 'TravelOrderSignatories';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'TravelOrderId',
        'UserId',
        'Rank',
        'Status',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'TravelOrderId' => 'string',
        'UserId' => 'string',
        'Status' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'TravelOrderId' => 'nullable|string|max:50',
        'UserId' => 'nullable|string|max:50',
        'Rank' => 'nullable',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string|max:1000'
    ];

    
}
