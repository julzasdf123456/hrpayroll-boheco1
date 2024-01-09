<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PayrollDetails
 * @package App\Models
 * @version December 13, 2021, 5:32 am UTC
 *
 * @property string $PayrolIndexId
 * @property string $EmployeeId
 * @property string $GrossSalary
 * @property string $TotalDeductions
 * @property string $AddOns
 * @property string $Vat
 * @property string $NetSalary
 */
class PayrollDetails extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'PayrollDetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'PayrolIndexId',
        'EmployeeId',
        'GrossSalary',
        'TotalDeductions',
        'AddOns',
        'Vat',
        'NetSalary'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'PayrolIndexId' => 'string',
        'EmployeeId' => 'string',
        'GrossSalary' => 'string',
        'TotalDeductions' => 'string',
        'AddOns' => 'string',
        'Vat' => 'string',
        'NetSalary' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'PayrolIndexId' => 'nullable|string|max:255',
        'EmployeeId' => 'nullable|string|max:255',
        'GrossSalary' => 'nullable|string|max:255',
        'TotalDeductions' => 'nullable|string|max:255',
        'AddOns' => 'nullable|string|max:255',
        'Vat' => 'nullable|string|max:255',
        'NetSalary' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
