<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveConversions extends Model
{
    public $table = 'LeaveConversions';

    public $fillable = [
        'id',
        'EmployeeId',
        'VacationDays',
        'SickDays',
        'VacationAmount',
        'SickAmount',
        'Year',
        'Status',
        'Notes',
        'UserId',
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'VacationDays' => 'decimal:2',
        'SickDays' => 'decimal:2',
        'VacationAmount' => 'decimal:2',
        'SickAmount' => 'decimal:2',
        'Year' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
        'UserId' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'VacationDays' => 'nullable|numeric',
        'SickDays' => 'nullable|numeric',
        'VacationAmount' => 'nullable|numeric',
        'SickAmount' => 'nullable|numeric',
        'Year' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'UserId' => 'nullable|string',
    ];

    
}
