<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Positions
 * @package App\Models
 * @version January 31, 2023, 3:13 am UTC
 *
 * @property string $Position
 * @property string $Description
 * @property string $Level
 * @property string $ParentPositionId
 * @property string $Notes
 */
class Positions extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Positions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Position',
        'Description',
        'Level',
        'ParentPositionId',
        'Notes',
        'BasicSalary',
        'Department'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Position' => 'string',
        'Description' => 'string',
        'Level' => 'string',
        'ParentPositionId' => 'string',
        'Notes' => 'string',
        'BasicSalary' => 'string',
        'Department' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Position' => 'required|string|max:450',
        'Description' => 'nullable|string|max:600',
        'Level' => 'nullable|string|max:255',
        'ParentPositionId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'BasicSalary' => 'nullable',
        'Department' => 'nullable|string'
    ];

    
}
