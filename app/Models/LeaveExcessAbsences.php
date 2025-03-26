<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveExcessAbsences extends Model
{
    public $table = 'LeaveExcessAbsences';

    public $fillable = [
        'id',
        'EmployeeId',
        'LeaveDate',
        'HoursAbsent', // converted to mins 
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'LeaveDate' => 'string',
        'HoursAbsent' => 'decimal:2',
        'Notes' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'LeaveDate' => 'nullable',
        'HoursAbsent' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string',
    ];

    
}
