<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripTicketGRS extends Model
{
    public $table = 'TripTicketGRS';

    public $fillable = [
        'id',
        'TripTicketId',
        'Purpose',
        'TotalMileage',
        'TotalLiters',
        'TypeOfFuel',
        'CarRatio',
        'Notes',
        'Vehicle'
    ];

    protected $casts = [
        'id' => 'string',
        'TripTicketId' => 'string',
        'Purpose' => 'string',
        'TotalMileage' => 'decimal:2',
        'TotalLiters' => 'string',
        'TypeOfFuel' => 'string',
        'CarRatio' => 'string',
        'Notes' => 'string',
        'Vehicle' => 'string',
    ];

    public static array $rules = [
        'TripTicketId' => 'nullable|string|max:80',
        'Purpose' => 'nullable|string|max:2000',
        'TotalMileage' => 'nullable|numeric',
        'TotalLiters' => 'nullable|string',
        'TypeOfFuel' => 'nullable|string|max:60',
        'CarRatio' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Vehicle' => 'nullable|string',
    ];

    
}
