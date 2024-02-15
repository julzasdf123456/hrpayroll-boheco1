<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bempc extends Model
{
    public $table = 'BEMPC';

    public $fillable = [
        'id',
        'DeductionFor',
        'EmployeeId',
        'DeductionSchedule',
        'Amount',
        'UserId',
        'Notes',
        'ZeroOutExcessAmount',
        'Year',
        'ReleaseType',
    ];

    protected $casts = [
        'id' => 'string',
        'DeductionFor' => 'string',
        'EmployeeId' => 'string',
        'DeductionSchedule' => 'date',
        'Amount' => 'decimal:2',
        'UserId' => 'string',
        'Notes' => 'string',
        'ZeroOutExcessAmount' => 'string',
        'Year' => 'string',
        'ReleaseType' => 'string',
    ];

    public static array $rules = [
        'DeductionFor' => 'nullable|string|max:100',
        'EmployeeId' => 'nullable|string|max:50',
        'DeductionSchedule' => 'nullable',
        'Amount' => 'nullable|numeric',
        'UserId' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ZeroOutExcessAmount' => 'nullable|string',
        'Year' => 'nullable|string',
        'ReleaseType' => 'nullable|string',
    ];

    
}
