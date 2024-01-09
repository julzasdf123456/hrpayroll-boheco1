<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LeaveBalanceDetails
 * @package App\Models
 * @version February 6, 2023, 1:26 am UTC
 *
 * @property string $EmployeeId
 * @property string $Method
 * @property number $Days
 * @property string $Details
 */
class LeaveBalanceDetails extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'LeaveBalanceDetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'EmployeeId',
        'Method',
        'Days',
        'Details',
        'Month',
        'LeaveType'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Method' => 'string',
        'Days' => 'decimal:2',
        'Details' => 'string',
        'Month' => 'string',
        'LeaveType' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:255',
        'Method' => 'nullable|string|max:255',
        'Days' => 'nullable|numeric',
        'Details' => 'nullable|string|max:2000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Month' => 'nullable|string',
        'LeaveType' => 'nullable|string',
    ];

    
}
