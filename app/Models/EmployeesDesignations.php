<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EmployeesDesignations
 * @package App\Models
 * @version November 13, 2021, 3:45 am UTC
 *
 * @property string $EmployeeId
 * @property string $Designation
 * @property string $Description
 * @property string $DateStarted
 * @property string $DateEnd
 * @property string $SalaryGrade
 * @property string $SalaryAmount
 * @property string $SalaryAddOns
 */
class EmployeesDesignations extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'EmployeesDesignations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'EmployeeId',
        'PositionId',
        'Description',
        'DateStarted',
        'DateEnd',
        'SalaryGrade',
        'SalaryAmount',
        'SalaryAddOns',
        'Status',
        'IsActive',
        'SubjectLoad',
        'SalaryPerLoad',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'PositionId' => 'string',
        'Description' => 'string',
        'DateStarted' => 'date',
        'DateEnd' => 'date',
        'SalaryGrade' => 'string',
        'SalaryAmount' => 'string',
        'SalaryAddOns' => 'string',
        'Status' => 'string',
        'IsActive' => 'string',
        'SubjectLoad' => 'string',
        'SalaryPerLoad' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:100',
        'PositionId' => 'nullable|string|max:200',
        'Description' => 'nullable|string|max:2000',
        'DateStarted' => 'nullable',
        'DateEnd' => 'nullable',
        'SalaryGrade' => 'nullable|string|max:200',
        'SalaryAmount' => 'nullable|string|max:300',
        'SalaryAddOns' => 'nullable|string|max:300',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Status' => 'nullable|string',
        'IsActive' => 'nullable|string',
        'SubjectLoad' => 'string|nullable',
        'SalaryPerLoad' => 'string|nullable',
    ];

    
}
