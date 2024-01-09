<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Towns
 * @package App\Models
 * @version September 9, 2021, 8:51 am UTC
 *
 * @property string $Town
 * @property string $District
 * @property string $Notes
 */
class Towns extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Towns';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'Town',
        'District',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'Town' => 'string',
        'District' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Town' => 'nullable|string|max:50',
        'District' => 'nullable|string|max:10',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
