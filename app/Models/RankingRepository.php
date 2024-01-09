<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class RankingRepository
 * @package App\Models
 * @version November 14, 2021, 2:49 am UTC
 *
 * @property string $Type
 * @property string $RankingName
 * @property string $Description
 * @property string $Points
 * @property string $Notes
 */
class RankingRepository extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'RankingRepository';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'Type',
        'RankingName',
        'Description',
        'Points',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'Type' => 'string',
        'RankingName' => 'string',
        'Description' => 'string',
        'Points' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Type' => 'nullable|string|max:2000',
        'RankingName' => 'nullable|string|max:1000',
        'Description' => 'nullable|string|max:1000',
        'Points' => 'nullable|string|max:10',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
