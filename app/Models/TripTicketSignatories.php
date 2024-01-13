<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripTicketSignatories extends Model
{
    public $table = 'TripTicketSignatories';

    public $fillable = [
        'id',
        'TripTicketId',
        'EmployeeId',
        'Rank',
        'Status',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'TripTicketId' => 'string',
        'EmployeeId' => 'string',
        'Status' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'TripTicketId' => 'nullable|string|max:255',
        'EmployeeId' => 'nullable|string|max:255',
        'Rank' => 'nullable',
        'Status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string|max:2000'
    ];

    
}
