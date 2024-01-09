<?php

namespace App\Repositories;

use App\Models\LeaveBalances;
use App\Repositories\BaseRepository;

/**
 * Class LeaveBalancesRepository
 * @package App\Repositories
 * @version February 6, 2023, 12:40 am UTC
*/

class LeaveBalancesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'Vacation',
        'Sick',
        'Special',
        'Maternity',
        'MaternityForSoloMother',
        'Paternity',
        'SoloParent',
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
        return LeaveBalances::class;
    }
}
