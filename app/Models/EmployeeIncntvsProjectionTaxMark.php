<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeIncntvsProjectionTaxMark extends Model
{
    public $table = 'EmployeeIncentivesProjectionTaxMark';

    public $fillable = [
        'id',
        'EmployeeId',
        'Incentive',
        'SalaryPeriod',
        'Deducted',
        'Amount',
        'Department',
        'EmployeeType',
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Incentive' => 'string',
        'SalaryPeriod' => 'date',
        'Deducted' => 'string',
        'Amount' => 'string',
        'Department' => 'string',
        'EmployeeType' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'Incentive' => 'nullable|string|max:1000',
        'SalaryPeriod' => 'nullable',
        'Deducted' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Amount' => 'nullable|string',
        'Department' => 'nullable|string',
        'EmployeeType' => 'nullable|string',
    ];

    
}
