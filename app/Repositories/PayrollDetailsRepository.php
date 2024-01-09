<?php

namespace App\Repositories;

use App\Models\PayrollDetails;
use App\Repositories\BaseRepository;

/**
 * Class PayrollDetailsRepository
 * @package App\Repositories
 * @version December 13, 2021, 5:32 am UTC
*/

class PayrollDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'PayrolIndexId',
        'EmployeeId',
        'GrossSalary',
        'TotalDeductions',
        'AddOns',
        'Vat',
        'NetSalary'
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
        return PayrollDetails::class;
    }
}
