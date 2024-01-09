<?php

namespace App\Repositories;

use App\Models\AttendanceData;
use App\Repositories\BaseRepository;

/**
 * Class AttendanceDataRepository
 * @package App\Repositories
 * @version February 2, 2023, 6:18 am UTC
*/

class AttendanceDataRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'BiometricUserId',
        'EmployeeId',
        'UserId',
        'Timestamp',
        'State',
        'UID'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AttendanceData::class;
    }
}
