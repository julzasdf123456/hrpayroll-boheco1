<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LeaveAttendanceDates
 * @package App\Models
 * @version December 5, 2021, 10:02 am UTC
 *
 * @property string $EmployeeId
 * @property string $DateOfLeave
 * @property string $LeaveId
 */
class LeaveAttendanceDates extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'LeaveAttendanceDates';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'EmployeeId',
        'DateOfLeave',
        'LeaveId'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'DateOfLeave' => 'date',
        'LeaveId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:255',
        'DateOfLeave' => 'nullable',
        'LeaveId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
