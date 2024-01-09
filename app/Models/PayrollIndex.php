<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PayrollIndex
 * @package App\Models
 * @version December 13, 2021, 5:27 am UTC
 *
 * @property string $DateFrom
 * @property string $DateTo
 * @property string $EmployeeType
 * @property string $Notes
 */
class PayrollIndex extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'PayrollIndex';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'DateFrom',
        'DateTo',
        'EmployeeType',
        'Notes',
        'SalaryPeriod',
        'Department',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'DateFrom' => 'date',
        'DateTo' => 'date',
        'EmployeeType' => 'string',
        'Notes' => 'string',
        'SalaryPeriod' => 'string',
        'Department' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'DateFrom' => 'nullable',
        'DateTo' => 'nullable',
        'EmployeeType' => 'string|max:255',
        'Notes' => 'string|max:255|nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'SalaryPeriod' => 'nullable',
        'Department' => 'nullable|string'
    ];

    
    public static function validateMorningHours($attendance) {
        if ($attendance->MorningIn==null | $attendance->MorningOut==null) {
            return false;
        } else {
            return true;
        }
    }

    public static function validateAfternoonHours($attendance) {
        if ($attendance->AfternoonIn==null | $attendance->AfternoonOut==null) {
            return false;
        } else {
            return true;
        }
    }

    public static function colorizeAttendance($attendance) {
        if ($attendance==0) {
            return 'text-danger';
        } else {
            if ($attendance >= 4) {
                return '';
            } elseif ($attendance < 4) {
                return 'text-warning';
            }
        }
    }

    public static function getTotalHours($startTime, $endTime, $startRule, $endRule) {
        if (($startTime <= $startRule) && ($endTime >= $endRule)) {
            // PUNCTUAL
            return 4;
        } elseif (($startTime > $startRule) && ($endTime >= $endRule)) {
            // LATE
            return abs(round(($endRule - $startTime)/3600, 1));
        } elseif (($startTime < $startRule) && ($endTime < $endRule)) {
            // UNDERTIME
            return abs(round(($endTime - $startRule)/3600, 1));
        } else {
            // ABSENT AND LATE
            return abs(round(($endTime - $startTime)/3600, 1));
        }
    }

    public static function getLateMinutes($startTime, $endTime, $startRule, $endRule) {
        if (($startTime <= $startRule) && ($endTime >= $endRule)) {
            // PUNCTUAL
            return 0;
        } elseif (($startTime > $startRule) && ($endTime >= $endRule)) {
            // LATE
            return abs(round(($startTime - $startRule)/60, 2));
        } elseif (($startTime < $startRule) && ($endTime < $endRule)) {
            // UNDERTIME
            return abs(round(($endRule - $endTime)/60, 2));
        } else {
            // ABSENT AND LATE
            $late = abs(round(($startTime - $startRule)/60, 2));
            $undertime = abs(round(($endRule - $endTime)/60, 2));
            return abs(round($late + $undertime, 2));
        }
    }

    public static function isAbsent($attendanceHours, $day) {
        if ($day == 'Sat' || $day == 'Sun') {
            return false;
        } else {
            if ($attendanceHours > 0) {
                return false;
            } else {
                return true;
            }            
        }
    }

    public static function colorSunday($day) {
        if ($day == 'Sun') {
            return 'payroll-color-sunday';
        } else {
            return '';
        }
    }

    public static function colorSaturday($day) {
        if ($day == 'Sat') {
            return 'payroll-color-saturday';
        } else {
            return '';
        }
    }
}
