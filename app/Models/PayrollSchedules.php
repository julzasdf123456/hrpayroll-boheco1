<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PayrollSchedules
 * @package App\Models
 * @version February 2, 2023, 10:48 am UTC
 *
 * @property string $Name
 * @property time $StartTime
 * @property time $BreakStart
 * @property time $BreakEnd
 * @property time $EndTime
 * @property string $Notes
 */
class PayrollSchedules extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'PayrollSchedules';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Name',
        'StartTime',
        'BreakStart',
        'BreakEnd',
        'EndTime',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Name' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Name' => 'nullable|string|max:300',
        'StartTime' => 'nullable',
        'BreakStart' => 'nullable',
        'BreakEnd' => 'nullable',
        'EndTime' => 'nullable',
        'Notes' => 'nullable|string|max:1500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
