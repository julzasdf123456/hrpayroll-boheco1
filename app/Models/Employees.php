<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Barangays;
use App\Models\Towns;

/**
 * Class Employees
 * @package App\Models
 * @version August 7, 2021, 11:52 am UTC
 *
 * @property string $FirstName
 * @property string $MiddleName
 * @property string $LastName
 * @property string $Suffix
 * @property string $Gender
 * @property string $Birthdate
 * @property string $StreetCurrent
 * @property string $BarangayCurrent
 * @property string $TownCurrent
 * @property string $ProvinceCurrent
 * @property string $StreetPermanent
 * @property string $BarangayPermanent
 * @property string $TownPermanent
 * @property string $ProvincePermanent
 * @property string $ContactNumbers
 * @property string $EmailAddress
 * @property string $BloodType
 * @property string $CivilStatus
 * @property string $Religion
 * @property string $Citizenship
 */
class Employees extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Employees';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'Gender',
        'Birthdate',
        'StreetCurrent',
        'BarangayCurrent',
        'TownCurrent',
        'ProvinceCurrent',
        'StreetPermanent',
        'BarangayPermanent',
        'TownPermanent',
        'ProvincePermanent',
        'ContactNumbers',
        'EmailAddress',
        'BloodType',
        'CivilStatus',
        'Religion',
        'Citizenship',
        'Designation',
        'BiometricsUserId',
        'PayrollScheduleId'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'FirstName' => 'string',
        'MiddleName' => 'string',
        'LastName' => 'string',
        'Suffix' => 'string',
        'Gender' => 'string',
        'Birthdate' => 'date',
        'StreetCurrent' => 'string',
        'BarangayCurrent' => 'string',
        'TownCurrent' => 'string',
        'ProvinceCurrent' => 'string',
        'StreetPermanent' => 'string',
        'BarangayPermanent' => 'string',
        'TownPermanent' => 'string',
        'ProvincePermanent' => 'string',
        'ContactNumbers' => 'string',
        'EmailAddress' => 'string',
        'BloodType' => 'string',
        'CivilStatus' => 'string',
        'Religion' => 'string',
        'Citizenship' => 'string',
        'Designation' => 'string',
        'BiometricsUserId' => 'string',
        'PayrollScheduleId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'FirstName' => 'nullable|string|max:400',
        'MiddleName' => 'nullable|string|max:400',
        'LastName' => 'nullable|string|max:400',
        'Suffix' => 'nullable|string|max:15',
        'Gender' => 'nullable|string|max:10',
        'Birthdate' => 'required',
        'StreetCurrent' => 'nullable|string|max:1000',
        'BarangayCurrent' => 'nullable|string|max:400',
        'TownCurrent' => 'nullable|string|max:400',
        'ProvinceCurrent' => 'nullable|string|max:400',
        'StreetPermanent' => 'nullable|string|max:1000',
        'BarangayPermanent' => 'nullable|string|max:400',
        'TownPermanent' => 'nullable|string|max:400',
        'ProvincePermanent' => 'nullable|string|max:400',
        'ContactNumbers' => 'nullable|string|max:500',
        'EmailAddress' => 'nullable|string|max:500',
        'BloodType' => 'nullable|string|max:10',
        'CivilStatus' => 'nullable|string|max:100',
        'Religion' => 'nullable|string|max:200',
        'Citizenship' => 'nullable|string|max:100',
        'created_at' => 'nullable|',
        'updated_at' => 'nullable',
        'Designation' => 'nullable|string',
        'BiometricsUserId' => 'nullable|string',
        'PayrollScheduleId' => 'nullable|string'
    ];

    public static function getMergeName($employee) {
        if ($employee != null) {
            return $employee->FirstName . " " . $employee->MiddleName . " " . $employee->LastName . " " . $employee->Suffix;
        } else {
            return "No name";
        }
    }

    public static function getCurrentAddress($employee) {
        if ($employee != null) {
            return $employee->StreetCurrent . ", " . (Barangays::find($employee->BarangayCurrent)==null ? '-' : Barangays::find($employee->BarangayCurrent)->Barangays) . ", " . (Towns::find($employee->TownCurrent)==null ? '-' : Towns::find($employee->TownCurrent)->Town) . ", " . $employee->ProvinceCurrent;
        } else {
            return "-";
        }
    }

    public static function getPermanentAddress($employee) {
        if ($employee != null) {
            return $employee->StreetPermanent . ", " . (Barangays::find($employee->BarangayPermanent)==null ? '-' : Barangays::find($employee->BarangayPermanent)->Barangays) . ", " . (Towns::find($employee->TownPermanent)==null ? '-' : Towns::find($employee->TownPermanent)->Town) . ", " . $employee->ProvincePermanent;
        } else {
            return "-";
        }
    }
}
