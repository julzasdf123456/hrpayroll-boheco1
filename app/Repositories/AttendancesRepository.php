<?php

namespace App\Repositories;

use App\Models\Attendances;
use App\Repositories\BaseRepository;

/**
 * Class AttendancesRepository
 * @package App\Repositories
 * @version November 28, 2021, 9:40 am UTC
*/

class AttendancesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'MorningTimeIn',
        'MorningTimeOut',
        'AfternoonTimeIn',
        'AfternoonTimeOut',
        'OTTimeIn',
        'OTTimeOut'
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
        return Attendances::class;
    }
}
