<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LeaveApplications
 * @package App\Models
 * @version November 21, 2021, 7:02 am UTC
 *
 * @property string $EmployeeId
 * @property string $DateFrom
 * @property string $DateTo
 * @property integer $NumberOfDays
 * @property string $Content
 * @property string $Status
 */
class LeaveApplications extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'LeaveApplications';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'EmployeeId',
        'DateFrom',
        'DateTo',
        'NumberOfDays',
        'Content',
        'Status',
        'LeaveType',
        'TotalCredits'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'DateFrom' => 'date',
        'DateTo' => 'date',
        'NumberOfDays' => 'integer',
        'Content' => 'string',
        'Status' => 'string',
        'LeaveType' => 'string',
        'TotalCredits' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:255',
        'DateFrom' => 'nullable',
        'DateTo' => 'nullable',
        'NumberOfDays' => 'nullable|integer',
        'Content' => 'nullable|string|max:2000',
        'Status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'LeaveType' => 'nullable|string',
        'TotalCredits' => 'nullable|string',
    ];

    
}
