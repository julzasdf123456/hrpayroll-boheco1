<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffsetSignatories extends Model
{
    public $table = 'OffsetSignatories';

    public $fillable = [
        'OffsetBatchId',
        'EmployeeId',
        'Rank',
        'Status',
        'Notes',
    ];

    protected $casts = [
        'OffsetBatchId' => 'string',
        'EmployeeId' => 'string',
        'Status' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'OffsetBatchId' => 'nullable|string|max:255',
        'EmployeeId' => 'nullable|string|max:255',
        'Rank' => 'nullable',
        'Status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string|max:2000'
    ];

    
}
