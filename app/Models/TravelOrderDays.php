<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelOrderDays extends Model
{
    public $table = 'TravelOrderDays';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'TravelOrderId',
        'Day',
        'Longevity'
    ];

    protected $casts = [
        'id' => 'string',
        'TravelOrderId' => 'string',
        'Day' => 'date',
        'Longevity' => 'string'
    ];

    public static array $rules = [
        'TravelOrderId' => 'nullable|string|max:80',
        'Day' => 'nullable',
        'Longevity' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
