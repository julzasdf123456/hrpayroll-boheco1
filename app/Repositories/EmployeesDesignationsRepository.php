<?php

namespace App\Repositories;

use App\Models\EmployeesDesignations;
use App\Repositories\BaseRepository;

/**
 * Class EmployeesDesignationsRepository
 * @package App\Repositories
 * @version November 13, 2021, 3:45 am UTC
*/

class EmployeesDesignationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'Designation',
        'Description',
        'DateStarted',
        'DateEnd',
        'SalaryGrade',
        'SalaryAmount',
        'SalaryAddOns'
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
        return EmployeesDesignations::class;
    }
}
