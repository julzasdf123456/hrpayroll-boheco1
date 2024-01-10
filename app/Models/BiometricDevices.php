<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiometricDevices extends Model
{
    public $table = 'BiometricDevices';

    public $fillable = [
        'id',
        'IPAddress',
        'Brand',
        'Office',
        'Status',
        'Notes',
        'DatetimeLastSynced'
    ];

    protected $casts = [
        'id' => 'string',
        'IPAddress' => 'string',
        'Brand' => 'string',
        'Office' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
        'DatetimeLastSynced' => 'string',
    ];

    public static array $rules = [
        'IPAddress' => 'nullable|string|max:50',
        'Brand' => 'nullable|string|max:50',
        'Office' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DatetimeLastSynced' => 'nullable',
    ];

    
}
