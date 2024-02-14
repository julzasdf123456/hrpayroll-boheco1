<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncentiveDetails extends Model
{
    public $table = 'IncentiveDetails';

    public $fillable = [
        'id',
        'EmployeeId',
        'IncentivesId',
        'SubTotal',
        'BasicSalary',
        'TermWage',
        'OtherDeductions',
        'BEMPC',
        'NetPay',
        'EmployeeType',
        'Department'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'IncentivesId' => 'string',
        'SubTotal' => 'decimal:2',
        'BasicSalary' => 'decimal:2',
        'TermWage' => 'decimal:2',
        'OtherDeductions' => 'decimal:2',
        'BEMPC' => 'decimal:2',
        'NetPay' => 'decimal:2',
        'EmployeeType' => 'string',
        'Department' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'IncentivesId' => 'nullable|string|max:80',
        'SubTotal' => 'nullable|numeric',
        'BasicSalary' => 'nullable|numeric',
        'TermWage' => 'nullable|numeric',
        'OtherDeductions' => 'nullable|numeric',
        'BEMPC' => 'nullable|numeric',
        'NetPay' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'EmployeeType' => 'nullable|string',
        'Department' => 'nullable|string',
    ];

    
}
