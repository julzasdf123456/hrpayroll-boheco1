<?php

namespace App\Repositories;

use App\Models\LeaveDays;
use App\Repositories\BaseRepository;

/**
 * Class LeaveDaysRepository
 * @package App\Repositories
 * @version February 1, 2023, 1:04 am UTC
*/

class LeaveDaysRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'LeaveId',
        'LeaveDate',
        'Longevity',
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
        return LeaveDays::class;
    }
}
