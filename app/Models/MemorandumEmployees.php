<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemorandumEmployees extends Model
{
    public $table = 'MemorandumEmployees';

    public $fillable = [
        'id',
        'MemoId',
        'EmployeeId'
    ];

    protected $casts = [
        'id' => 'string',
        'MemoId' => 'string',
        'EmployeeId' => 'string'
    ];

    public static array $rules = [
        'MemoId' => 'nullable|string|max:60',
        'EmployeeId' => 'nullable|string|max:60',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
