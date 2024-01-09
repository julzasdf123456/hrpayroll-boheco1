<?php

namespace App\Repositories;

use App\Models\PayrollSchedules;
use App\Repositories\BaseRepository;

/**
 * Class PayrollSchedulesRepository
 * @package App\Repositories
 * @version February 2, 2023, 10:48 am UTC
*/

class PayrollSchedulesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Name',
        'StartTime',
        'BreakStart',
        'BreakEnd',
        'EndTime',
        'Notes'
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
        return PayrollSchedules::class;
    }
}
