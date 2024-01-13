<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LeaveSignatories
 * @package App\Models
 * @version November 21, 2021, 7:03 am UTC
 *
 * @property string $LeaveId
 * @property string $EmployeeId
 * @property integer $Rank
 * @property string $Status
 */
class LeaveSignatories extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'LeaveSignatories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'LeaveId',
        'EmployeeId',
        'Rank',
        'Status',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'LeaveId' => 'string',
        'EmployeeId' => 'string',
        'Rank' => 'integer',
        'Status' => 'string',
        'Notes' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'LeaveId' => 'nullable|string|max:255',
        'EmployeeId' => 'nullable|string|max:255',
        'Rank' => 'nullable|integer',
        'Status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string',
    ];

    
}
