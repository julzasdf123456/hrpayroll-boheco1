<?php

namespace App\Repositories;

use App\Models\ProfessionalIDs;
use App\Repositories\BaseRepository;

/**
 * Class ProfessionalIDsRepository
 * @package App\Repositories
 * @version November 21, 2021, 5:22 am UTC
*/

class ProfessionalIDsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'Entity',
        'EntityId'
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
        return ProfessionalIDs::class;
    }
}
