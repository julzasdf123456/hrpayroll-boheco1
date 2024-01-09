<?php

namespace App\Repositories;

use App\Models\LeaveAttendanceDates;
use App\Repositories\BaseRepository;

/**
 * Class LeaveAttendanceDatesRepository
 * @package App\Repositories
 * @version December 5, 2021, 10:02 am UTC
*/

class LeaveAttendanceDatesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'DateOfLeave',
        'LeaveId'
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
        return LeaveAttendanceDates::class;
    }
}
