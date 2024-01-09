<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AttendanceRules
 * @package App\Models
 * @version November 28, 2021, 10:39 am UTC
 *
 * @property time $MorningTimeInStart
 * @property time $MorningTimeInEnd
 * @property time $MorningTimeOutStart
 * @property time $MorningTimeOutEnd
 * @property time $AfternoonTimeInStart
 * @property time $AfternoonTimeInEnd
 * @property time $AfternoonTimeOutStart
 * @property time $AfternoonTimeOutEnd
 */
class AttendanceRules extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'AttendanceRules';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'MorningTimeInStart',
        'MorningTimeInEnd',
        'MorningAbsentThreshold',
        'MorningUndertimeThreshold',
        'MorningTimeOutStart',
        'MorningTimeOutEnd',
        'AfternoonTimeInStart',
        'AfternoonTimeInEnd',
        'AfternoonTimeOutStart',
        'AfternoonTimeOutEnd',
        'AfternoonAbsentThreshold',
        'AfternoonUndertimeThreshold'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'MorningTimeInStart' => 'nullable',
        'MorningTimeInEnd' => 'nullable',
        'MorningTimeOutStart' => 'nullable',
        'MorningTimeOutEnd' => 'nullable',
        'AfternoonTimeInStart' => 'nullable',
        'AfternoonTimeInEnd' => 'nullable',
        'AfternoonTimeOutStart' => 'nullable',
        'AfternoonTimeOutEnd' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'MorningAbsentThreshold' => 'nullable',
        'MorningUndertimeThreshold' => 'nullable',
        'AfternoonAbsentThreshold' => 'nullable',
        'AfternoonUndertimeThreshold' => 'nullable',
    ];

    
}
