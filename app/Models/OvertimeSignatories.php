<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeSignatories extends Model
{
    public $table = 'OvertimeSignatories';

    public $fillable = [
        'id',
        'OvertimeId',
        'EmployeeId',
        'Rank',
        'Status',
        'Notes'
    ];

    protected $casts = [
        'id' => 'string',
        'OvertimeId' => 'string',
        'EmployeeId' => 'string',
        'Status' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'OvertimeId' => 'nullable|string|max:255',
        'EmployeeId' => 'nullable|string|max:255',
        'Rank' => 'nullable',
        'Status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string|max:2000'
    ];

    
}
