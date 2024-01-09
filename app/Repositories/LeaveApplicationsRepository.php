<?php

namespace App\Repositories;

use App\Models\LeaveApplications;
use App\Repositories\BaseRepository;

/**
 * Class LeaveApplicationsRepository
 * @package App\Repositories
 * @version November 21, 2021, 7:02 am UTC
*/

class LeaveApplicationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'DateFrom',
        'DateTo',
        'NumberOfDays',
        'Content',
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
        return LeaveApplications::class;
    }
}
