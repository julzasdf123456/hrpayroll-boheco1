<?php

namespace App\Models;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class UserFootprints extends Model
{
    public $table = 'UserFootprints';

    public $fillable = [
        'id',
        'UserId',
        'LogName',
        'LogDetails',
        'ComputerName',
        'ObjectSourceId',
    ];

    protected $casts = [
        'id' => 'string',
        'UserId' => 'string',
        'LogName' => 'string',
        'LogDetails' => 'string',
        'ComputerName' => 'string',
        'ObjectSourceId' => 'string',
    ];

    public static array $rules = [
        'UserId' => 'nullable|string|max:50',
        'LogName' => 'nullable|string|max:50',
        'LogDetails' => 'nullable|string|max:1500',
        'ComputerName' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ObjectSourceId' => 'nullable|string',
    ];

    public static function log($name, $details) {
        UserFootprints::create([
            'id' => IDGenerator::generateIDandRandString(),
            'LogName' => $name,
            'LogDetails' => $details,
            'UserId' => Auth::id(),
        ]);
    }
    
    public static function logSource($name, $details, $sourceId) {
        UserFootprints::create([
            'id' => IDGenerator::generateIDandRandString(),
            'LogName' => $name,
            'LogDetails' => $details,
            'ObjectSourceId' => $sourceId,
            'UserId' => Auth::id(),
        ]);
    }
}
