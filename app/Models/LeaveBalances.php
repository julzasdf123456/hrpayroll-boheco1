<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LeaveBalances
 * @package App\Models
 * @version February 6, 2023, 12:40 am UTC
 *
 * @property string $EmployeeId
 * @property number $Vacation
 * @property number $Sick
 * @property number $Special
 * @property number $Maternity
 * @property number $MaternityForSoloMother
 * @property number $Paternity
 * @property number $SoloParent
 * @property string $Notes
 */
class LeaveBalances extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'LeaveBalances';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'EmployeeId',
        'Vacation', // minutes (days = bal / 8 / 60)
        'Sick', // minutes (days = bal / 8 / 60)
        'Special', // days
        'Maternity', // days
        'MaternityForSoloMother', // days
        'Paternity', // days
        'SoloParent', // days
        'Notes', 
        'Month',
        'Year',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Vacation' => 'decimal:2',
        'Sick' => 'decimal:2',
        'Special' => 'decimal:2',
        'Maternity' => 'decimal:2',
        'MaternityForSoloMother' => 'decimal:2',
        'Paternity' => 'decimal:2',
        'SoloParent' => 'decimal:2',
        'Notes' => 'string',
        'Month' => 'string',
        'Year' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EmployeeId' => 'nullable|string|max:255',
        'Vacation' => 'nullable|numeric',
        'Sick' => 'nullable|numeric',
        'Special' => 'nullable|numeric',
        'Maternity' => 'nullable|numeric',
        'MaternityForSoloMother' => 'nullable|numeric',
        'Paternity' => 'nullable|numeric',
        'SoloParent' => 'nullable|numeric',
        'Notes' => 'nullable|string|max:300',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Month' => 'nullable|string',
        'Year' => 'nullable|string'
    ];

    public static function toDay($mins) {
        if ($mins != null && is_numeric($mins)) {
            return round($mins / 8 / 60, 2);
        } else {
            return 0;
        }
    }

    public static function toExpanded($mins) {

        if ($mins != null && is_numeric($mins) && $mins > 0) {
            $days = (int) ($mins / 8 / 60);
            
            // hours
            $exactDayInMins = $days * 8 * 60;
            $excessMins = $mins - $exactDayInMins;
            $hours = (int) ($excessMins / 60);

            // mins
            $totalMins = ($days * 8 * 60) + ($hours * 60);
            $excessMins = $mins - $totalMins;

            return $days . ' days, ' . $hours . ' hrs, ' . $excessMins . ' mins';
        } else {
            return 0 . ' days, ' . 0 . ' hrs, ' . 0 . ' mins';
        }
    }

    public static function toExpandedHtml($mins) {
        if ($mins != null && is_numeric($mins) && $mins > 0) {
            $days = (int) ($mins / 8 / 60);
            
            // hours
            $exactDayInMins = $days * 8 * 60;
            $excessMins = $mins - $exactDayInMins;
            $hours = (int) ($excessMins / 60);

            // mins
            $totalMins = ($days * 8 * 60) + ($hours * 60);
            $excessMins = $mins - $totalMins;

            return '<span class="text-lg text-primary">' . $days . '</span> days, <span class="text-lg text-primary">' . $hours . '</span> hrs, <span class="text-lg text-primary">' . $excessMins . '</span> mins';
        } else {
            return '<span class="text-lg text-primary">' . 0 . '</span> days, <span class="text-lg text-primary">' . 0 . '</span> hrs, <span class="text-lg text-primary">' . 0 . '</span> mins';
        }
    }

    public static function toBalanceArray($mins) {
        if ($mins != null && is_numeric($mins) && $mins > 0) {
            $days = (int) ($mins / 8 / 60);
            
            // hours
            $exactDayInMins = $days * 8 * 60;
            $excessMins = $mins - $exactDayInMins;
            $hours = (int) ($excessMins / 60);

            // mins
            $totalMins = ($days * 8 * 60) + ($hours * 60);
            $excessMins = $mins - $totalMins;

            // return [
            //     'Days' => $days,
            //     'Hours' => $hours,
            //     'Minutes' => $excessMins,
            // ];
            return [ $days, $hours, $excessMins ];
        } else {
            return 0 . ' days, ' . 0 . ' hrs, ' . 0 . ' mins';
        }
    }

    public static function toBalanceAssocArray($mins) {
        if ($mins != null && is_numeric($mins) && $mins > 0) {
            $days = (int) ($mins / 8 / 60);
            
            // hours
            $exactDayInMins = $days * 8 * 60;
            $excessMins = $mins - $exactDayInMins;
            $hours = (int) ($excessMins / 60);

            // mins
            $totalMins = ($days * 8 * 60) + ($hours * 60);
            $excessMins = $mins - $totalMins;

            // return [
            //     'Days' => $days,
            //     'Hours' => $hours,
            //     'Minutes' => $excessMins,
            // ];
            return [ 'days' => $days . '', 'hours' => $hours . '', 'minutes' => $excessMins . '' ];
        } else {
            return [ 'days' => '0', 'hours' => '0', 'minutes' => '0' ];
        }
    }
}
