<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ProfessionalIDs
 * @package App\Models
 * @version November 21, 2021, 5:22 am UTC
 *
 * @property string $EmployeeId
 * @property string $Entity
 * @property string $EntityId
 */
class ProfessionalIDs extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'ProfessionalIDs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'EmployeeId',
        'Entity',
        'EntityId',
        'ContributionAmount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'Entity' => 'string',
        'EntityId' => 'string',
        'ContributionAmount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:255',
        'Entity' => 'nullable|string|max:100',
        'EntityId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ContributionAmount' => 'nullable|string'
    ];

    
}
