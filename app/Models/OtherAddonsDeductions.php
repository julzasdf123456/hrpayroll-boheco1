<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherAddonsDeductions extends Model
{
    public $table = 'OtherAddonsDeductions';

    public $fillable = [
        'id',
        'EmployeeId',
        'DeductionName',
        'DeductionDescription',
        'ScheduleDate',
        'Notes',
        'Status',
        'DeductionAmount',
        'Type',
        'AddonName',
        'AddonAmount'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'DeductionName' => 'string',
        'DeductionDescription' => 'string',
        'ScheduleDate' => 'date',
        'Notes' => 'string',
        'Status' => 'string',
        'DeductionAmount' => 'decimal:2',
        'Type' => 'string',
        'AddonName' => 'string',
        'AddonAmount' => 'decimal:2'
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:80',
        'DeductionName' => 'nullable|string|max:150',
        'DeductionDescription' => 'nullable|string|max:500',
        'ScheduleDate' => 'nullable',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Status' => 'nullable|string|max:50',
        'DeductionAmount' => 'nullable|numeric',
        'Type' => 'nullable|string|max:50',
        'AddonName' => 'nullable|string|max:150',
        'AddonAmount' => 'nullable|numeric'
    ];

    
}
