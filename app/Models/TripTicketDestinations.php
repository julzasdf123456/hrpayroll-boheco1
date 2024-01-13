<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripTicketDestinations extends Model
{
    public $table = 'TripTicketDestinations';

    public $fillable = [
        'id',
        'TripTicketId',
        'DestinationAddress',
        'BarangayId',
        'TownId',
        'EmployeeId',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'TripTicketId' => 'string',
        'DestinationAddress' => 'string',
        'BarangayId' => 'string',
        'TownId' => 'string',
        'EmployeeId' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'TripTicketId' => 'nullable|string|max:80',
        'DestinationAddress' => 'nullable|string|max:800',
        'BarangayId' => 'nullable|string|max:50',
        'TownId' => 'nullable|string|max:50',
        'EmployeeId' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
