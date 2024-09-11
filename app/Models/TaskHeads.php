<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskHeads extends Model
{
    public $table = 'TaskHeads';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'Title',
        'Description',
        'Status', // to do, doing, done
    ];

    protected $casts = [
        'id' => 'string',
        'Title' => 'string',
        'Description' => 'string',
        'Status' => 'string'
    ];

    public static array $rules = [
        'Title' => 'nullable|string|max:250',
        'Description' => 'nullable|string|max:2500',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
