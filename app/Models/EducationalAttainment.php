<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EducationalAttainment
 * @package App\Models
 * @version November 21, 2021, 3:50 am UTC
 *
 * @property string $EmployeeId
 * @property string $Type
 * @property string $Major
 * @property string $School
 * @property string $SchoolYear
 */
class EducationalAttainment extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'EducationalAttainment';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'EmployeeId',
        'Type',
        'Major',
        'School',
        'SchoolYear',
        'Certification'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'Type' => 'string',
        'Major' => 'string',
        'School' => 'string',
        'SchoolYear' => 'string',
        'Certification' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:255',
        'Type' => 'nullable|string|max:255',
        'Major' => 'nullable|string|max:500',
        'School' => 'nullable|string|max:1000',
        'SchoolYear' => 'nullable|string|max:60',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Certification' => 'nullable|string'
    ];

    
}
