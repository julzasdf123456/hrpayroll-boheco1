<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    public $table = 'Vehicles';

    public $fillable = [
        'id',
        'VehicleName',
        'PlateNumber',
        'Brand',
        'Model',
        'Notes',
        'DesignatedDriver',
    ];

    protected $casts = [
        'id' => 'string',
        'VehicleName' => 'string',
        'PlateNumber' => 'string',
        'Brand' => 'string',
        'Model' => 'string',
        'Notes' => 'string',
        'DesignatedDriver' => 'string',
    ];

    public static array $rules = [
        'VehicleName' => 'nullable|string|max:500',
        'PlateNumber' => 'nullable|string|max:50',
        'Brand' => 'nullable|string|max:50',
        'Model' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DesignatedDriver' => 'nullable|string',
    ];

    
}
