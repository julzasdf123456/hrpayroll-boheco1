<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskChecklists extends Model
{
    public $table = 'TaskChecklists';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'TaskHeadId',
        'Title',
        'Description',
        'TargetDate',
        'DueDate',
        'Status', // completed, null=pending, incomplete
        'Notes',
    ];

    protected $casts = [
        'id' => 'string',
        'TaskHeadId' => 'string',
        'Title' => 'string',
        'Description' => 'string',
        'TargetDate' => 'string',
        'DueDate' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
    ];

    public static array $rules = [
        'TaskHeadId' => 'nullable|string|max:80',
        'Title' => 'nullable|string|max:500',
        'Description' => 'nullable|string|max:2000',
        'TargetDate' => 'nullable',
        'DueDate' => 'nullable',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string',
    ];

    
}
