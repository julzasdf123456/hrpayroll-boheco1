<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncentivesAnnualProjection extends Model
{
    public $table = 'IncentivesAnnualProjection';

    public $fillable = [
        'id',
        'Year',
        'Incentive',
        'IncentiveDescription',
        'Amount',
        'IsTaxable',
        'MaxUntaxableAmount'
    ];

    protected $casts = [
        'id' => 'string',
        'Year' => 'string',
        'Incentive' => 'string',
        'IncentiveDescription' => 'string',
        'Amount' => 'decimal:2',
        'IsTaxable' => 'string',
        'MaxUntaxableAmount' => 'decimal:2'
    ];

    public static array $rules = [
        'id' => 'nullable|string',
        'Year' => 'nullable|string|max:50',
        'Incentive' => 'nullable|string|max:500',
        'IncentiveDescription' => 'nullable|string|max:1000',
        'Amount' => 'nullable|numeric',
        'IsTaxable' => 'nullable|string|max:50',
        'MaxUntaxableAmount' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
