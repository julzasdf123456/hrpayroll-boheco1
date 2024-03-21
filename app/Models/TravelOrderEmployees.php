<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelOrderEmployees extends Model
{
    public $table = 'TravelOrderEmployees';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'TravelOrderId',
        'EmployeeId',
        'Status'
    ];

    protected $casts = [
        'id' => 'string',
        'TravelOrderId' => 'string',
        'EmployeeId' => 'string',
        'Status' => 'string'
    ];

    public static array $rules = [
        'TravelOrderId' => 'nullable|string|max:80',
        'EmployeeId' => 'nullable|string|max:80',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
