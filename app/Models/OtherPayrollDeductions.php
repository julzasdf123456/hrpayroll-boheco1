<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherPayrollDeductions extends Model
{
    public $table = 'OtherPayrollDeductions';

    public $fillable = [
        'id',
        'EmployeeId',
        'DeductionName',
        'DeductionDescription',
        'ScheduleDate',
        'Notes',
        'Status',
        'Amount',
        'Type', // Salary Adjustments, Others
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'DeductionName' => 'string',
        'DeductionDescription' => 'string',
        'ScheduleDate' => 'date',
        'Notes' => 'string',
        'Status' => 'string',
        'Amount' => 'string',
        'Type' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:80',
        'DeductionName' => 'nullable|string|max:150',
        'DeductionDescription' => 'nullable|string|max:500',
        'ScheduleDate' => 'nullable',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Status' => 'nullable|string',
        'Amount' => 'nullable|string',
        'Type' => 'nullable|string',
    ];

    
}
