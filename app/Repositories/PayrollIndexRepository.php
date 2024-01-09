<?php

namespace App\Repositories;

use App\Models\PayrollIndex;
use App\Repositories\BaseRepository;

/**
 * Class PayrollIndexRepository
 * @package App\Repositories
 * @version December 13, 2021, 5:27 am UTC
*/

class PayrollIndexRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'DateFrom',
        'DateTo',
        'EmployeeType',
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
        return PayrollIndex::class;
    }
}
