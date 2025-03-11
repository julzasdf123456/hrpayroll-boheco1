<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LeaveDays
 * @package App\Models
 * @version February 1, 2023, 1:04 am UTC
 *
 * @property string $LeaveId
 * @property string $LeaveDate
 * @property number $Longevity
 * @property string $Notes
 */
class LeaveDays extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'LeaveDays';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'LeaveId',
        'LeaveDate',
        'Longevity',
        'Notes',
        'Duration',
        'Status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'LeaveId' => 'string',
        'LeaveDate' => 'string',
        'Longevity' => 'float',
        'Notes' => 'string',
        'Duration' => 'string',
        'Status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'LeaveId' => 'required|string|max:255',
        'LeaveDate' => 'nullable',
        'Longevity' => 'nullable|numeric',
        'Notes' => 'nullable|string|max:2000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Duration' => 'nullable|string',
        'Status' => 'nullable|string'
    ];

    
}
