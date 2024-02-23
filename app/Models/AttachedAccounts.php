<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttachedAccounts extends Model
{
    public $table = 'AttachedAccounts';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'EmployeeId',
        'AccountNumber',
        'Status',
        'ConsumerName',
        'ConsumerAddress',
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'AccountNumber' => 'string',
        'Status' => 'string',
        'ConsumerName' => 'string',
        'ConsumerAddress' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'AccountNumber' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ConsumerName' => 'nullable|string',
        'ConsumerAddress' => 'nullable|string',
    ];

    
}
