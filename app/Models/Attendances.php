<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Attendances
 * @package App\Models
 * @version November 28, 2021, 9:40 am UTC
 *
 * @property string $EmployeeId
 * @property string|\Carbon\Carbon $MorningTimeIn
 * @property string|\Carbon\Carbon $MorningTimeOut
 * @property string|\Carbon\Carbon $AfternoonTimeIn
 * @property string|\Carbon\Carbon $AfternoonTimeOut
 * @property string|\Carbon\Carbon $OTTimeIn
 * @property string|\Carbon\Carbon $OTTimeOut
 */
class Attendances extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Attendances';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'EmployeeId',
        'LogTime',
        'Type',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'LogTime' => 'datetime',
        'Type' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:60',
        'LogTime' => 'datetime|nullable',
        'Type' => 'datetime|nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
