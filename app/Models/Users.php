<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Users
 * @package App\Models
 * @version August 7, 2021, 11:51 am UTC
 *
 * @property string $employee_id
 * @property string $name
 * @property string $email
 * @property string|\Carbon\Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 */
class Users extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'employee_id',
        'username',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'ColorProfile',
        'ProfilePicture',
        'ColorProfile',
        'OTP'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'employee_id' => 'string',
        'username' => 'string',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'ColorProfile' => 'string',
        'ProfilePicture' => 'string',
        'ColorProfile' => 'string',
        'OTP' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'employee_id' => 'nullable|string|max:255',
        'username' => 'nullable|string',
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'email_verified_at' => 'nullable',
        'password' => 'nullable|string|max:255',
        'remember_token' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ColorProfile' => 'nullable|string',
        'ProfilePicture' => 'nullable|string',
        'ColorProfile' => 'nullable|string',
        'OTP' => 'nullable|string',
    ];

    
}
