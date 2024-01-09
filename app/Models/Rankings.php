<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Rankings
 * @package App\Models
 * @version November 14, 2021, 2:49 am UTC
 *
 * @property string $EmployeeId
 * @property string $RankingRepositoryId
 * @property string $Notes
 */
class Rankings extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Rankings';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'EmployeeId',
        'RankingRepositoryId',
        'Notes',
        'Points'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'RankingRepositoryId' => 'string',
        'Notes' => 'string',
        'Points' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'RankingRepositoryId' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Points' => 'nullable|string'
    ];

    
}
