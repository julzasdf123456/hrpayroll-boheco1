<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncentivesYearEndDetails extends Model
{
    public $table = 'IncentivesYearEndDetails';

    public $fillable = [
        'id',
        'IncentivesId',
        'EmployeeId',
        'FourteenthMonthPay',
        'ThirteenthMonthDifferential',
        'CashGift',
        'VacationLeave',
        'SickLeave',
        'LoyaltyAward',
        'SubTotal',
        'AROthers',
        'BEMPC',
        'WithholdingTaxes',
        'NetPay',
        'UserId',
        'EmployeeType',
        'Department',
    ];

    protected $casts = [
        'id' => 'string',
        'IncentivesId' => 'string',
        'EmployeeId' => 'string',
        'FourteenthMonthPay' => 'decimal:2',
        'ThirteenthMonthDifferential' => 'decimal:2',
        'CashGift' => 'decimal:2',
        'VacationLeave' => 'decimal:2',
        'SickLeave' => 'decimal:2',
        'LoyaltyAward' => 'decimal:2',
        'SubTotal' => 'decimal:2',
        'AROthers' => 'decimal:2',
        'BEMPC' => 'decimal:2',
        'WithholdingTaxes' => 'decimal:2',
        'NetPay' => 'decimal:2',
        'UserId' => 'string',
        'EmployeeType' => 'string',
        'Department' => 'string'
    ];

    public static array $rules = [
        'IncentivesId' => 'nullable|string|max:60',
        'EmployeeId' => 'nullable|string|max:50',
        'FourteenthMonthPay' => 'nullable|numeric',
        'ThirteenthMonthDifferential' => 'nullable|numeric',
        'CashGift' => 'nullable|numeric',
        'VacationLeave' => 'nullable|numeric',
        'SickLeave' => 'nullable|numeric',
        'LoyaltyAward' => 'nullable|numeric',
        'SubTotal' => 'nullable|numeric',
        'AROthers' => 'nullable|numeric',
        'BEMPC' => 'nullable|numeric',
        'WithholdingTaxes' => 'nullable|numeric',
        'NetPay' => 'nullable|numeric',
        'UserId' => 'nullable|string|max:50',
        'EmployeeType' => 'nullable|string|max:50',
        'Department' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
