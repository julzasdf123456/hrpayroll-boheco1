<?php

namespace App\Repositories;

use App\Models\EmployeePayrollSchedules;
use App\Repositories\BaseRepository;

/**
 * Class EmployeePayrollSchedulesRepository
 * @package App\Repositories
 * @version February 2, 2023, 10:39 am UTC
*/

class EmployeePayrollSchedulesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'ScheduleId'
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
        return EmployeePayrollSchedules::class;
    }
}
