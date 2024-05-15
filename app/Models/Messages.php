<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    public $table = 'Messages';

    public $fillable = [
        'id',
        'Sender',
        'Receiver',
        'Message',
        'Status'
    ];

    protected $casts = [
        'id' => 'string',
        'Sender' => 'string',
        'Receiver' => 'string',
        'Message' => 'string',
        'Status' => 'string'
    ];

    public static array $rules = [
        'Sender' => 'nullable|string|max:50',
        'Receiver' => 'nullable|string|max:50',
        'Message' => 'nullable|string|max:16',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
