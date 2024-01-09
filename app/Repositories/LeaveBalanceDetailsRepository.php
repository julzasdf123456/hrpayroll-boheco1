<?php

namespace App\Repositories;

use App\Models\LeaveBalanceDetails;
use App\Repositories\BaseRepository;

/**
 * Class LeaveBalanceDetailsRepository
 * @package App\Repositories
 * @version February 6, 2023, 1:26 am UTC
*/

class LeaveBalanceDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'Method',
        'Days',
        'Details'
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
        return LeaveBalanceDetails::class;
    }
}
