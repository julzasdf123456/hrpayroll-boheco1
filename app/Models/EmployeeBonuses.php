<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBonuses extends Model
{
    public $table = 'EmployeeBonuses';

    public $fillable = [
        'EmployeeId',
        'Incentive',
        'IncentiveDescription',
        'BaseAmount',
        'MaxUntaxableAmount',
        'DeductUponReceipt',
        'TaxDeductionAmount',
        'NetAmountPay',
        'DateReleased',
        'Notes',
        'Year'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Incentive' => 'string',
        'IncentiveDescription' => 'string',
        'BaseAmount' => 'decimal:2',
        'MaxUntaxableAmount' => 'decimal:2',
        'DeductUponReceipt' => 'string',
        'TaxDeductionAmount' => 'decimal:2',
        'NetAmountPay' => 'decimal:2',
        'DateReleased' => 'date',
        'Notes' => 'string',
        'Year' => 'string'
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'Incentive' => 'nullable|string|max:300',
        'IncentiveDescription' => 'nullable|string|max:600',
        'BaseAmount' => 'nullable|numeric',
        'MaxUntaxableAmount' => 'nullable|numeric',
        'DeductUponReceipt' => 'nullable|string|max:50',
        'TaxDeductionAmount' => 'nullable|numeric',
        'NetAmountPay' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DateReleased' => 'nullable',
        'Notes' => 'nullable|string|max:1500',
        'Year' => 'nullable|string|max:50'
    ];

    
}
