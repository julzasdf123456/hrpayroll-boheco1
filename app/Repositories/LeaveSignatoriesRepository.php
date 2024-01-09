<?php

namespace App\Repositories;

use App\Models\LeaveSignatories;
use App\Repositories\BaseRepository;

/**
 * Class LeaveSignatoriesRepository
 * @package App\Repositories
 * @version November 21, 2021, 7:03 am UTC
*/

class LeaveSignatoriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'LeaveId',
        'EmployeeId',
        'Rank',
        'Status'
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
        return LeaveSignatories::class;
    }
}
