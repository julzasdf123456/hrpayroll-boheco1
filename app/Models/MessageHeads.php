<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageHeads extends Model
{
    public $table = 'MessageHeads';

    public $fillable = [
        'id',
        'Sender',
        'Receiver',
        'LatestMessage',
        'Status',
    ];

    protected $casts = [
        'id' => 'string',
        'Sender' => 'string',
        'Receiver' => 'string',
        'LatestMessage' => 'string',
        'Status' => 'string',
    ];

    public static array $rules = [
        'Sender' => 'nullable|string|max:50',
        'Receiver' => 'nullable|string|max:50',
        'LatestMessage' => 'nullable|string|max:16',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Status' => 'nullable|string',
    ];

    
}
