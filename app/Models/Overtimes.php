<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Overtimes
 * @package App\Models
 * @version December 14, 2021, 3:35 am UTC
 *
 * @property string $EmployeeId
 * @property string $DateOfOT
 * @property string $From
 * @property string $To
 * @property string $Notes
 */
class Overtimes extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Overtimes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'EmployeeId',
        'DateOfOT',
        'From',
        'To',
        'Notes',
        'Multiplier',
        'DateOTEnded',
        'TypeOfDay',
        'PurposeOfOT',
        'UserId',
        'TotalHours',
        'MaxHourThreshold',
        'Status',
        'OTAmount',
        'FinanceUserApproved',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'EmployeeId' => 'string',
        'DateOfOT' => 'string',
        'Notes' => 'string',
        'Multiplier' => 'string',
        'DateOTEnded' => 'string',
        'TypeOfDay' => 'string',
        'PurposeOfOT' => 'string',
        'UserId' => 'string',
        'TotalHours' => 'string',
        'MaxHourThreshold' => 'string',
        'Status' => 'string',
        'From' => 'string',
        'To' => 'string',
        'OTAmount' => 'string',
        'FinanceUserApproved' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'required|string|max:255',
        'DateOfOT' => 'nullable',
        'From' => 'nullable',
        'To' => 'nullable',
        'Notes' => 'nullable|string|max:1500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Multiplier' => 'nullable|string',
        'DateOTEnded' => 'nullable|string',
        'TypeOfDay' => 'nullable|string',
        'PurposeOfOT' => 'nullable|string',
        'UserId' => 'nullable|string',
        'TotalHours' => 'nullable|string',        
        'MaxHourThreshold' => 'nullable|string',
        'Status' => 'nullable|string',
        'OTAmount' => 'nullable|string',
        'FinanceUserApproved' => 'nullable|string',
    ];

    public static function getStatusColor($status) {
        if ($status != null) {
            if ($status == 'APPROVED') {
                return 'bg-success';
            } else {
                return 'bg-danger';
            }
        } else {
            return 'bg-warning';
        }
    }
}
