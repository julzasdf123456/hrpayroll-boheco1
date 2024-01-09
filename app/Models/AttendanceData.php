<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AttendanceData
 * @package App\Models
 * @version February 2, 2023, 6:18 am UTC
 *
 * @property string $BiometricUserId
 * @property string $EmployeeId
 * @property string $UserId
 * @property string|\Carbon\Carbon $Timestamp
 * @property string $State
 * @property string $UID
 */
class AttendanceData extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'AttendanceData';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BiometricUserId',
        'EmployeeId',
        'UserId',
        'Timestamp',
        'State',
        'UID',
        'DeviceIp',
        'AbsentPermission',
        'Type', // AM IN, AM OUT, PM IN, PM OUT, OT IN, OT OUT
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'BiometricUserId' => 'string',
        'EmployeeId' => 'string',
        'UserId' => 'string',
        'Timestamp' => 'datetime',
        'State' => 'string',
        'UID' => 'string',
        'DeviceIp' => 'string',
        'AbsentPermission' => 'string',
        'Type' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'BiometricUserId' => 'nullable|string|max:255',
        'EmployeeId' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'Timestamp' => 'nullable',
        'State' => 'nullable|string|max:255',
        'UID' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DeviceIp' => 'string|nullable',
        'AbsentPermission' => 'string|nullable',
        'Type' => 'nullable|string',
    ];

    
}
