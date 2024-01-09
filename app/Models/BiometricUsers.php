<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BiometricUsers
 * @package App\Models
 * @version February 2, 2023, 5:01 am UTC
 *
 * @property string $UID
 * @property string $Name
 * @property string $UserId
 * @property string $Role
 * @property string $Notes
 */
class BiometricUsers extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'BiometricUsers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'UID',
        'Name',
        'UserId',
        'Role',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'UID' => 'string',
        'Name' => 'string',
        'UserId' => 'string',
        'Role' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'UID' => 'nullable|string|max:255',
        'Name' => 'nullable|string|max:250',
        'UserId' => 'nullable|string|max:255',
        'Role' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
