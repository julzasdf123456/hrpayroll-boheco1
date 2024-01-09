<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EmployeeImages
 * @package App\Models
 * @version December 8, 2021, 8:17 am UTC
 *
 * @property string $EmployeeId
 * @property string $Image
 * @property string $Description
 */
class EmployeeImages extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'EmployeeImages';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'EmployeeId',
        'Image',
        'Description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'Image' => 'string',
        'Description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:60',
        'Image' => 'nullable|string|max:255',
        'Description' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
