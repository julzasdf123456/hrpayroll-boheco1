<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePayrollSundries extends Model
{
    public $table = 'EmployeePayrollSundries';

    public $fillable = [
        'id',
        'EmployeeId',
        'Longevity',
        'RiceAllowance',
        'Insurances',
        'PagIbigContribution',
        'PagIbigLoan',
        'SSSContribution',
        'SSSLoan',
        'PhilHealth',
        'Notes',
        'PagIbigContributionEmployer',
        'SSSContributionEmployer',
        'PhilHealthContributionEmployer',
        'PagIbigMP2'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Longevity' => 'decimal:2',
        'RiceAllowance' => 'decimal:2',
        'Insurances' => 'decimal:2',
        'PagIbigContribution' => 'decimal:2',
        'PagIbigLoan' => 'decimal:2',
        'SSSContribution' => 'decimal:2',
        'SSSLoan' => 'decimal:2',
        'PhilHealth' => 'decimal:2',
        'Notes' => 'string',
        'PagIbigContributionEmployer' => 'string',
        'SSSContributionEmployer' => 'string',
        'PhilHealthContributionEmployer' => 'string',
        'PagIbigMP2' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'Longevity' => 'nullable|numeric',
        'RiceAllowance' => 'nullable|numeric',
        'Insurances' => 'nullable|numeric',
        'PagIbigContribution' => 'nullable|numeric',
        'PagIbigLoan' => 'nullable|numeric',
        'SSSContribution' => 'nullable|numeric',
        'SSSLoan' => 'nullable|numeric',
        'PhilHealth' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string|max:2000',
        'PagIbigContributionEmployer' => 'nullable|string',
        'SSSContributionEmployer' => 'nullable|string',
        'PhilHealthContributionEmployer' => 'nullable|string',
        'PagIbigMP2' => 'nullable|string',
    ];

    
}
