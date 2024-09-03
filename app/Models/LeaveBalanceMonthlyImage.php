<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalanceMonthlyImage extends Model
{
    public $table = 'LeaveBalanceMonthlyImage';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'EmployeeId',
        'Vacation',
        'Sick',
        'Special',
        'Maternity',
        'MaternityForSoloMother',
        'Paternity',
        'SoloParent',
        'Notes',
        'Month',
        'Year'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Vacation' => 'decimal:2',
        'Sick' => 'decimal:2',
        'Special' => 'decimal:2',
        'Maternity' => 'decimal:2',
        'MaternityForSoloMother' => 'decimal:2',
        'Paternity' => 'decimal:2',
        'SoloParent' => 'decimal:2',
        'Notes' => 'string',
        'Month' => 'string',
        'Year' => 'string'
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:255',
        'Vacation' => 'nullable|numeric',
        'Sick' => 'nullable|numeric',
        'Special' => 'nullable|numeric',
        'Maternity' => 'nullable|numeric',
        'MaternityForSoloMother' => 'nullable|numeric',
        'Paternity' => 'nullable|numeric',
        'SoloParent' => 'nullable|numeric',
        'Notes' => 'nullable|string|max:300',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Month' => 'nullable|string|max:50',
        'Year' => 'nullable|string|max:50'
    ];

    
}
