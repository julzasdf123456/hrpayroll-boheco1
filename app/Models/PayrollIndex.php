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

    /**
     * FOR PayrollIndex Payments in Payroll
     */
    public static function isNonResidential($consumerType) {
        if ($consumerType == 'CS' || $consumerType == 'CL' || $consumerType == 'I') {
            return true;
        } else {
            return false;
        }
    }

    public static function getSurchargableAmount($bill) {
        $netAmount = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
        $excemptions = floatval($bill->ACRM_TAFPPCA != null ? $bill->ACRM_TAFPPCA : '0') +
                        floatval($bill->DAA_GRAM != null ? $bill->DAA_GRAM : '0') +
                        floatval($bill->Others != null ? $bill->Others : '0') +
                        floatval($bill->GenerationVAT != null ? $bill->GenerationVAT : '0') +
                        floatval($bill->TransmissionVAT != null ? $bill->TransmissionVAT : '0') +
                        floatval($bill->SLVAT != null ? $bill->SLVAT : '0') +
                        floatval($bill->DistributionVAT != null ? $bill->DistributionVAT : '0') +
                        floatval($bill->OthersVAT != null ? $bill->OthersVAT : '0') +
                        floatval($bill->DAA_VAT != null ? $bill->DAA_VAT : '0') +
                        floatval($bill->ACRM_VAT != null ? $bill->ACRM_VAT : '0') +
                        floatval($bill->FBHCAmt != null ? $bill->FBHCAmt : '0') +
                        floatval($bill->Item16 != null ? $bill->Item16 : '0') +
                        floatval($bill->Item17 != null ? $bill->Item17 : '0') +
                        floatval($bill->PR);
        return round($netAmount - $excemptions, 2);
    }

    public static function getSurchargableAmountNetMetering($bill) {
        $netAmount = $bill->NetMeteringNetAmount != null ? floatval($bill->NetMeteringNetAmount) : 0;
        $excemptions = floatval($bill->ACRM_TAFPPCA != null ? $bill->ACRM_TAFPPCA : '0') +
                        floatval($bill->DAA_GRAM != null ? $bill->DAA_GRAM : '0') +
                        floatval($bill->Others != null ? $bill->Others : '0') +
                        floatval($bill->GenerationVAT != null ? $bill->GenerationVAT : '0') +
                        floatval($bill->TransmissionVAT != null ? $bill->TransmissionVAT : '0') +
                        floatval($bill->SLVAT != null ? $bill->SLVAT : '0') +
                        floatval($bill->DistributionVAT != null ? $bill->DistributionVAT : '0') +
                        floatval($bill->OthersVAT != null ? $bill->OthersVAT : '0') +
                        floatval($bill->DAA_VAT != null ? $bill->DAA_VAT : '0') +
                        floatval($bill->ACRM_VAT != null ? $bill->ACRM_VAT : '0') +
                        floatval($bill->FBHCAmt != null ? $bill->FBHCAmt : '0') +
                        floatval($bill->Item16 != null ? $bill->Item16 : '0') +
                        floatval($bill->Item17 != null ? $bill->Item17 : '0') +
                        floatval($bill->PR);

        $amnt = round($netAmount - $excemptions, 2);

        if ($amnt < 0) {
            return 0;
        } else {
            return round($netAmount - $excemptions, 2);
        }
    }

    public static function computeSurcharge($bill) {
        if (PayrollIndex::isNonResidential($bill->ConsumerType)) {
            // IF CS, CL, I
            if (floatval($bill->PowerKWH) > 1000) {
                // IF MORE THAN 1000 KWH
                
                if (date('Y-m-d') > date('Y-m-d', strtotime($bill->DueDate . ' +30 days'))) {
                    // IF MORE THAN 30 days of due date
                    return (PayrollIndex::getSurchargableAmount($bill) * .05) + ((PayrollIndex::getSurchargableAmount($bill) * .05) * .12);
                } else {
                    if (date('Y-m-d') > date('Y-m-d', strtotime($bill->DueDate))) {
                        return (PayrollIndex::getSurchargableAmount($bill) * .03) + ((PayrollIndex::getSurchargableAmount($bill) * .03) * .12);
                    } else {
                        // NO SURCHARGE
                        return 0;
                    }
                }
            } else {
                // IF LESS THAN 1000 KWH
                if (date('Y-m-d') > date('Y-m-d', strtotime($bill->DueDate))) {
                    return (PayrollIndex::getSurchargableAmount($bill) * .03) + ((PayrollIndex::getSurchargableAmount($bill) * .03) * .12);
                } else {
                    // NO SURCHARGE
                    return 0;
                }
            }
        } else {
            if ($bill->ConsumerType == 'P') {
                // IF PUBLIC BUILDING, NO SURCHARGE
                return 0;
            } else {
                // RESIDENTIALS
                if (date('Y-m-d') > date('Y-m-d', strtotime($bill->DueDate))) {
                    if (floatval($bill->NetAmount) > 1667) {
                        return (PayrollIndex::getSurchargableAmount($bill) * .03) + ((PayrollIndex::getSurchargableAmount($bill) * .03) * .12);
                    } else {
                        return 56;
                    }
                } else {
                    // NO SURCHARGE
                    return 0;
                }
            }
        }
    }

    public static function computeSurchargeNetMetered($bill) {
        if (PayrollIndex::isNonResidential($bill->ConsumerType)) {
            // IF CS, CL, I
            if (floatval($bill->PowerKWH) > 1000) {
                // IF MORE THAN 1000 KWH
                
                if (date('Y-m-d') > date('Y-m-d', strtotime($bill->DueDate . ' +30 days'))) {
                    // IF MORE THAN 30 days of due date
                    return (PayrollIndex::getSurchargableAmountNetMetering($bill) * .05) + ((PayrollIndex::getSurchargableAmountNetMetering($bill) * .05) * .12);
                } else {
                    if (date('Y-m-d') > date('Y-m-d', strtotime($bill->DueDate))) {
                        return (PayrollIndex::getSurchargableAmountNetMetering($bill) * .03) + ((PayrollIndex::getSurchargableAmountNetMetering($bill) * .03) * .12);
                    } else {
                        // NO SURCHARGE
                        return 0;
                    }
                }
            } else {
                // IF LESS THAN 1000 KWH
                if (date('Y-m-d') > date('Y-m-d', strtotime($bill->DueDate))) {
                    return (PayrollIndex::getSurchargableAmountNetMetering($bill) * .03) + ((PayrollIndex::getSurchargableAmountNetMetering($bill) * .03) * .12);
                } else {
                    // NO SURCHARGE
                    return 0;
                }
            }
        } else {
            if ($bill->ConsumerType == 'P') {
                // IF PUBLIC BUILDING, NO SURCHARGE
                return 0;
            } else {
                // RESIDENTIALS
                if (date('Y-m-d') > date('Y-m-d', strtotime($bill->DueDate))) {
                    if (floatval($bill->NetMeteringNetAmount) > 1667) {
                        return (PayrollIndex::getSurchargableAmountNetMetering($bill) * .03) + ((PayrollIndex::getSurchargableAmountNetMetering($bill) * .03) * .12);
                    } else {
                        return 56;
                    }
                } else {
                    // NO SURCHARGE
                    return 0;
                }
            }
        }
    }

    public static function getSurcharge($bill) {
        if ($bill->ComputeMode == 'NetMetered') {
            $surcharge = PayrollIndex::computeSurchargeNetMetered($bill);

            if ($surcharge == 0) {
                return 0;
            } else {
                if ($surcharge < 56) {
                    return 56;
                } else {
                    return $surcharge;
                }
            }
        } else {
            $surcharge = PayrollIndex::computeSurcharge($bill);

            if ($surcharge == 0) {
                return 0;
            } else {
                if ($surcharge < 56) {
                    return 56;
                } else {
                    return $surcharge;
                }
            }
        }
    }
}
