<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripTicketPassengers extends Model
{
    public $table = 'TripTicketPassengers';

    public $fillable = [
        'id',
        'TripTicketId',
        'EmployeeId',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'TripTicketId' => 'string',
        'EmployeeId' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'TripTicketId' => 'nullable|string|max:80',
        'EmployeeId' => 'nullable|string|max:80',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
