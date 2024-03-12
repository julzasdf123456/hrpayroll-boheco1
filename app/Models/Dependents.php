<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dependents extends Model
{
    public $table = 'Dependents';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'EmployeeId',
        'DependentName',
        'Address',
        'Relationship',
        'Birthdate',
        'IsBeneficiary',
        'Occupation',
        'Disability',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'DependentName' => 'string',
        'Address' => 'string',
        'Relationship' => 'string',
        'Birthdate' => 'date',
        'IsBeneficiary' => 'string',
        'Occupation' => 'string',
        'Disability' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'DependentName' => 'nullable|string|max:160',
        'Address' => 'nullable|string|max:100',
        'Relationship' => 'nullable|string|max:50',
        'Birthdate' => 'nullable',
        'IsBeneficiary' => 'nullable|string|max:50',
        'Occupation' => 'nullable|string|max:50',
        'Disability' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string|max:500'
    ];

    
}
