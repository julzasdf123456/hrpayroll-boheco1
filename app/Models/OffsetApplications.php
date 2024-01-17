<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffsetApplications extends Model
{
    public $table = 'OffsetApplications';

    public $fillable = [
        'id',
        'PreparedBy', // userid
        'DatePrepared',
        'EmployeeId',
        'DateOfDuty',
        'TimeStart',
        'TimeEnd',
        'PurposeOfDuty',
        'DateOfOffset',
        'OffsetReason',
        'Status',
        'OffsetBatchId'
    ];

    protected $casts = [
        'id' => 'string',
        'PreparedBy' => 'string',
        'DatePrepared' => 'date',
        'EmployeeId' => 'string',
        'DateOfDuty' => 'date',
        'PurposeOfDuty' => 'string',
        'DateOfOffset' => 'date',
        'OffsetReason' => 'string',
        'Status' => 'string',
        'OffsetBatchId' => 'string',
    ];

    public static array $rules = [
        'PreparedBy' => 'nullable|string|max:50',
        'DatePrepared' => 'nullable',
        'EmployeeId' => 'nullable|string|max:80',
        'DateOfDuty' => 'nullable',
        'TimeStart' => 'nullable',
        'TimeEnd' => 'nullable',
        'PurposeOfDuty' => 'nullable|string|max:3000',
        'DateOfOffset' => 'nullable',
        'OffsetReason' => 'nullable|string|max:3000',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'OffsetBatchId' => 'nullable|string',
    ];

    
}
