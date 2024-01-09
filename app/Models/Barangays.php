<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Barangays
 * @package App\Models
 * @version September 9, 2021, 8:52 am UTC
 *
 * @property string $Barangays
 * @property string $TownId
 * @property string $Notes
 */
class Barangays extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Barangays';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'Barangays',
        'TownId',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'Barangays' => 'string',
        'TownId' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Barangays' => 'nullable|string|max:100',
        'TownId' => 'nullable|string|max:10',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
