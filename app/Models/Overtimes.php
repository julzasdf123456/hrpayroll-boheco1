<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Overtimes
 * @package App\Models
 * @version December 14, 2021, 3:35 am UTC
 *
 * @property string $EmployeeId
 * @property string $DateOfOT
 * @property time $From
 * @property time $To
 * @property string $Notes
 */
class Overtimes extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Overtimes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'EmployeeId',
        'DateOfOT',
        'From',
        'To',
        'Notes',
        'Multiplier'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'DateOfOT' => 'date',
        'Notes' => 'string',
        'Multiplier' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'required|string|max:255',
        'DateOfOT' => 'nullable',
        'From' => 'nullable',
        'To' => 'nullable',
        'Notes' => 'nullable|string|max:1500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Multiplier' => 'nullable|string'
    ];

    
}
