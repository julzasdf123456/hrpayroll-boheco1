<?php

namespace App\Repositories;

use App\Models\AttendanceRules;
use App\Repositories\BaseRepository;

/**
 * Class AttendanceRulesRepository
 * @package App\Repositories
 * @version November 28, 2021, 10:39 am UTC
*/

class AttendanceRulesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'MorningTimeInStart',
        'MorningTimeInEnd',
        'MorningTimeOutStart',
        'MorningTimeOutEnd',
        'AfternoonTimeInStart',
        'AfternoonTimeInEnd',
        'AfternoonTimeOutStart',
        'AfternoonTimeOutEnd'
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
        return AttendanceRules::class;
    }
}
