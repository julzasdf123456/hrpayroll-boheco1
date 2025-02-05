<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendaneConfirmationSignatories extends Model
{
    public $table = 'AttendanceConfirmationSignatories';

    public $fillable = [
        'id',
        'AttendanceConfirmationId',
        'EmployeeId',
        'Rank',
        'Status',
        'Notes',
    ];

    protected $casts = [
        'AttendanceConfirmationId' => 'string',
        'EmployeeId' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
        'id' => 'string'
    ];

    public static array $rules = [
        'AttendanceConfirmationId' => 'nullable|string|max:255',
        'EmployeeId' => 'nullable|string|max:255',
        'Rank' => 'nullable',
        'Status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string|max:2000'
    ];

    
}
