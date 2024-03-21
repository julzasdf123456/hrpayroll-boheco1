<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelOrders extends Model
{
    public $table = 'TravelOrders';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'DateFiled',
        'Destination',
        'Purpose',
        'UserId',
        'Status'
    ];

    protected $casts = [
        'id' => 'string',
        'DateFiled' => 'date',
        'Destination' => 'string',
        'Purpose' => 'string',
        'UserId' => 'string',
        'Status' => 'string'
    ];

    public static array $rules = [
        'DateFiled' => 'nullable',
        'Destination' => 'nullable|string|max:1000',
        'Purpose' => 'nullable|string|max:2000',
        'UserId' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
