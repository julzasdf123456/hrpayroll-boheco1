<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Barangays;
use App\Models\Towns;
use Illuminate\Support\Facades\DB;
use \DateTime;

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
        'PayrollScheduleId',
        'AuthorizedToDrive',
        'NoAttendanceAllowed',
        'DayOffDates',
        'Longevity',
        'OfficeDesignation',
        'DateHired',
        'EmploymentStatus',
        'DateEnded',
        'PrimaryBankNumber',
        'PrimaryBank',
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
        'PayrollScheduleId' => 'string',
        'AuthorizedToDrive' => 'string',
        'NoAttendanceAllowed' => 'string',
        'DayOffDates' => 'string',
        'Longevity' => 'string',
        'OfficeDesignation' => 'string',
        'DateHired' => 'string',
        'EmploymentStatus' => 'string',
        'DateEnded' => 'string',
        'PrimaryBankNumber' => 'string',
        'PrimaryBank' => 'string',
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
        'PayrollScheduleId' => 'nullable|string',
        'AuthorizedToDrive' => 'nullable|string',
        'NoAttendanceAllowed' => 'nullable|string',
        'DayOffDates' => 'nullable|string',
        'Longevity' => 'nullable|string',
        'OfficeDesignation' => 'nullable|string',
        'DateHired' => 'nullable|string',
        'EmploymentStatus' => 'nullable|string',
        'DateEnded' => 'nullable|string',
        'PrimaryBankNumber' => 'nullable|string',
        'PrimaryBank' => 'nullable|string',
    ];

    public static function getMergeName($employee) {
        if ($employee != null) {
            return $employee->FirstName . " " . $employee->MiddleName . " " . $employee->LastName . " " . $employee->Suffix;
        } else {
            return "No name";
        }
    }

    public static function getDriverMergeName($employee) {
        if ($employee != null) {
            return $employee->DriverFirstName . " " . $employee->DriverMiddleName . " " . $employee->DriverLastName . " " . $employee->DriverSuffix;
        } else {
            return "No name";
        }
    }

    public static function getMergeNameFormal($employee) {
        if ($employee != null) {
            return  $employee->LastName . ', ' . $employee->FirstName . " " . $employee->MiddleName . " " . $employee->Suffix;
        } else {
            return "No name";
        }
    }

    public static function getMergeNameFull($employee) {
        if ($employee != null) {
            return  $employee->LastName . ', ' . $employee->FirstName . " " . $employee->Suffix . " " . $employee->MiddleName;
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

    public static function getSupers($employeeId, $levelArrayFilter /** FILTERS THE LEVELS YOU WANT TO FETCH */) {
        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.LastName', 'Positions.Position', 'Positions.Department', 'Positions.ParentPositionId')
            ->whereRaw("Employees.id='" . $employeeId . "'")
            ->orderByDesc('EmployeesDesignations.DateStarted')
            ->first();

        $signatories = [];
        if ($employee != null && $employee->ParentPositionId != null) {
            // LOOP SIGNATORIES AND FETCH UPPER LEVEL POSITIONS
            $parentPosId = $employee->ParentPositionId;
            $dept = $employee->Department;
            $sign = true;
            $i = 0;
            $rank = 0;

            while ($sign) {
                $signatoryParents = DB::table('users')
                    ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
                    ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
                    ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                    ->select('users.id', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Level', 'Positions.Position', 'Positions.ParentPositionId', 'Positions.id AS PositionId')
                    ->whereRaw("Positions.id='" . $parentPosId . "' ")
                    ->first();

                if ($i > 5) {
                    $sign = false;
                    break;
                } else {
                    if ($signatoryParents != null && $signatoryParents->id != null) {
                        if ($levelArrayFilter == null | count($levelArrayFilter) == 0) {
                            array_push($signatories, [
                                'id' => $signatoryParents->id,
                                'FirstName' => $signatoryParents->FirstName,
                                'LastName' => $signatoryParents->LastName,
                                'MiddleName' => $signatoryParents->MiddleName,
                                'Suffix' => $signatoryParents->Suffix,
                                'Position' => $signatoryParents->Position,
                                'PositionId' => $signatoryParents->PositionId,
                                'ParentPositionId' => $signatoryParents->ParentPositionId,
                                'Level' => $signatoryParents->Level,
                            ]);
                        } else {
                            if (in_array($signatoryParents->Level, $levelArrayFilter)) {
                                array_push($signatories, [
                                    'id' => $signatoryParents->id,
                                    'FirstName' => $signatoryParents->FirstName,
                                    'LastName' => $signatoryParents->LastName,
                                    'MiddleName' => $signatoryParents->MiddleName,
                                    'Suffix' => $signatoryParents->Suffix,
                                    'Position' => $signatoryParents->Position,
                                    'PositionId' => $signatoryParents->PositionId,
                                    'ParentPositionId' => $signatoryParents->ParentPositionId,
                                    'Level' => $signatoryParents->Level,
                                ]);
                            }
                        }

                        $parentPosId = $signatoryParents->ParentPositionId;
                        $sign = true;
                        $i++;
                    } else {
                        $sign = false;
                        break;
                    }
                }
            }
        }

        return $signatories;
    }

    public static function getYearsFromDateHired($dateHired) {
        $now = new DateTime();
        $dateHired = new DateTime($dateHired);

        $interval = $dateHired->diff($now);

        return $interval->y;
    }

    public static function getWholeYearLongevity($employee, $year) {
        $dateHired = new DateTime($employee->DateHired);
        $startDate = date('Y-m-d', strtotime('January ' . date('d', strtotime($employee->DateHired)) . ', ' . $year));

        $longevityData = [];

        for($i=0; $i<12; $i++) {
            $runningDate = date('Y-m-d', strtotime($startDate . ' +' . $i . ' months'));
            $endDate = new DateTime($runningDate);

            $interval = $dateHired->diff($endDate);

            $noOfYears = $interval->y;

            $longevity = 0;
            if ($noOfYears == 5) {
                $longevity = 100;
            } elseif ($noOfYears < 5) {
                $longevity = 0;
            } else {
                $excessYears = $noOfYears - 5;

                $longevity = 100 + ($excessYears * 50);
            }

            array_push($longevityData, [
                "Month" => $runningDate,
                "Years" => $noOfYears,
                "Longevity" => $longevity,
            ]);
        }

        return $longevityData;
    }

    public static function getTotalLongevityProjection($employee, $year) {
        $data = Employees::getWholeYearLongevity($employee, $year);
        $longevityProjection = 0;

        foreach($data as $item) {
            $longevityProjection += $item['Longevity'] != null ? floatval($item['Longevity']) : 0;
        }

        return $longevityProjection;
    }

    public static function getDailyRate($salary) {
        if ($salary != null) {
            return round((floatval($salary) * 12) / 302, 2);
        } else {
            return 0;
        }
    }

    public static function getDepartmentFull($abrev) {
        if ($abrev === 'ESD') {
            return 'Engineering Services Department';
        } elseif ($abrev === 'ISD') {
            return 'Institutional Services Department';
        } elseif ($abrev === 'OGM') {
            return 'Office of the General Manager';
        } elseif ($abrev === 'PGD') {
            return 'Power Genration Department';
        } elseif ($abrev === 'OSD') {
            return 'Office Services Department';
        } elseif ($abrev === 'SEEAD') {
            return 'Special Equipment and Energy Audit Department';
        } elseif ($abrev === 'SUB-OFFICE') {
            return 'Sub-Office Area';
        } else {
            return $abrev;
        }
    }
}
