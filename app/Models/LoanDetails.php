<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDetails extends Model
{
    public $table = 'LoanDetails';

    public $fillable = [
        'id',
        'LoanId',
        'Interest',
        'Principal',
        'MonthlyAmmortization',
        'Month',
        'Paid',
        'ForwardedBalance'
    ];

    protected $casts = [
        'id' => 'string',
        'LoanId' => 'string',
        'Interest' => 'decimal:2',
        'Principal' => 'decimal:2',
        'MonthlyAmmortization' => 'decimal:2',
        'Month' => 'date',
        'Paid' => 'string',
        'ForwardedBalance' => 'string',
    ];

    public static array $rules = [
        'LoanId' => 'nullable|string|max:80',
        'Interest' => 'nullable|numeric',
        'Principal' => 'nullable|numeric',
        'MonthlyAmmortization' => 'nullable|numeric',
        'Month' => 'nullable',
        'Paid' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ForwardedBalance' => 'nullable|string',
    ];

    
}
