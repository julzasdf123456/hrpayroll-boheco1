<?php

namespace App\Repositories;

use App\Models\EducationalAttainment;
use App\Repositories\BaseRepository;

/**
 * Class EducationalAttainmentRepository
 * @package App\Repositories
 * @version November 21, 2021, 3:50 am UTC
*/

class EducationalAttainmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'Type',
        'Major',
        'School',
        'SchoolYear'
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
        return EducationalAttainment::class;
    }
}
