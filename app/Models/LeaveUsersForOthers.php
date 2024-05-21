<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveUsersForOthers extends Model
{
    public $table = 'LeaveUsersForOthers';

    public $fillable = [
        'id',
        'LeaveCreator',
        'EmployeeId',
        'Department',
        'Status',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'LeaveCreator' => 'string',
        'EmployeeId' => 'string',
        'Department' => 'string',
        'Status' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'LeaveCreator' => 'nullable|string|max:50',
        'EmployeeId' => 'nullable|string|max:50',
        'Department' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
