<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LeaveBalances
 * @package App\Models
 * @version February 6, 2023, 12:40 am UTC
 *
 * @property string $EmployeeId
 * @property number $Vacation
 * @property number $Sick
 * @property number $Special
 * @property number $Maternity
 * @property number $MaternityForSoloMother
 * @property number $Paternity
 * @property number $SoloParent
 * @property string $Notes
 */
class LeaveBalances extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'LeaveBalances';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'EmployeeId',
        'Vacation',
        'Sick',
        'Special',
        'Maternity',
        'MaternityForSoloMother',
        'Paternity',
        'SoloParent',
        'Notes',
        'Month',
        'Year',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Vacation' => 'decimal:2',
        'Sick' => 'decimal:2',
        'Special' => 'decimal:2',
        'Maternity' => 'decimal:2',
        'MaternityForSoloMother' => 'decimal:2',
        'Paternity' => 'decimal:2',
        'SoloParent' => 'decimal:2',
        'Notes' => 'string',
        'Month' => 'string',
        'Year' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:255',
        'Vacation' => 'nullable|numeric',
        'Sick' => 'nullable|numeric',
        'Special' => 'nullable|numeric',
        'Maternity' => 'nullable|numeric',
        'MaternityForSoloMother' => 'nullable|numeric',
        'Paternity' => 'nullable|numeric',
        'SoloParent' => 'nullable|numeric',
        'Notes' => 'nullable|string|max:300',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Month' => 'nullable|string',
        'Year' => 'nullable|string'
    ];

    
}
