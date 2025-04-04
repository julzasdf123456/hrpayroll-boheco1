<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripTicketLogs extends Model
{
    use HasFactory, HasUuids;

    public $table = 'TripTicketLogs';

    public $fillable = [
        'id',
        'TripTicketId',
        'GuardId',
        'Status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'string',
        'TripTicketId' => 'string',
        'GuardId' => 'string',
        'Status' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public static array $rules = [
        'TripTicketId' => 'nullable|string|max:50',
        'GuardId' => 'nullable|string|max:50',
        'Status' => 'string|nullable'
    ];
}
