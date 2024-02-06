<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeIncentiveAnnualProjections extends Model
{
    public $table = 'EmployeeIncentiveAnnualProjections';

    public $fillable = [
        'EmployeeId',
        'Year',
        'Incentive',
        'IncentiveDescription',
        'Amount',
        'IsTaxable',
        'MaxUntaxableAmount',
        'DeductMonthly'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Year' => 'string',
        'Incentive' => 'string',
        'IncentiveDescription' => 'string',
        'Amount' => 'decimal:2',
        'IsTaxable' => 'string',
        'MaxUntaxableAmount' => 'decimal:2',
        'DeductMonthly' => 'string'
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:60',
        'Year' => 'nullable|string|max:50',
        'Incentive' => 'nullable|string|max:500',
        'IncentiveDescription' => 'nullable|string|max:1000',
        'Amount' => 'nullable|numeric',
        'IsTaxable' => 'nullable|string|max:50',
        'MaxUntaxableAmount' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DeductMonthly' => 'nullable|string|max:50'
    ];

    
}
