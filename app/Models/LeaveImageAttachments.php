<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LeaveImageAttachments
 * @package App\Models
 * @version February 6, 2023, 9:10 am UTC
 *
 * @property string $LeaveId
 * @property string $HexImage
 */
class LeaveImageAttachments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'LeaveImageAttachments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'LeaveId',
        'HexImage'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'LeaveId' => 'string',
        'HexImage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'LeaveId' => 'nullable|string|max:255',
        'HexImage' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
