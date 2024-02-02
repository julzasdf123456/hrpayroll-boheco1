<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loans extends Model
{
    public $table = 'Loans';

    public $fillable = [
        'id',
        'LoanFor', // Pag-Ibig, SSS, Motorcycle
        'LoanName',
        'LoanDescription',
        'InterestRate',
        'LoanAmount',
        'Terms',
        'TermUnit',
        'EmployeeId',
        'PaymentTerm', // 15, 30, 15/30
        'MonthlyAmmortization',
    ];

    protected $casts = [
        'id' => 'string',
        'LoanFor' => 'string',
        'LoanName' => 'string',
        'LoanDescription' => 'string',
        'InterestRate' => 'decimal:2',
        'LoanAmount' => 'decimal:2',
        'Terms' => 'decimal:2',
        'TermUnit' => 'string',
        'EmployeeId' => 'string',
        'PaymentTerm' => 'string',
        'MonthlyAmmortization' => 'string',
    ];

    public static array $rules = [
        'LoanFor' => 'nullable|string|max:300',
        'LoanName' => 'nullable|string|max:500',
        'LoanDescription' => 'nullable|string|max:3000',
        'InterestRate' => 'nullable|numeric',
        'LoanAmount' => 'nullable|numeric',
        'Terms' => 'nullable|numeric',
        'TermUnit' => 'nullable|string|max:50',
        'EmployeeId' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'PaymentTerm' => 'nullable|string|max:50',
        'MonthlyAmmortization' => 'nullable|string',
    ];

    
}
