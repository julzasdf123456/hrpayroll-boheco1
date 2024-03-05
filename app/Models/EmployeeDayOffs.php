<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDayOffs extends Model
{
    public $table = 'EmployeeDayOffs';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'EmployeeId',
        'DayOff',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'DayOff' => 'date',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'DayOff' => 'nullable',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
