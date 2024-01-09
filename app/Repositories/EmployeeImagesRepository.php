<?php

namespace App\Repositories;

use App\Models\EmployeeImages;
use App\Repositories\BaseRepository;

/**
 * Class EmployeeImagesRepository
 * @package App\Repositories
 * @version December 8, 2021, 8:17 am UTC
*/

class EmployeeImagesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'Image',
        'Description'
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
        return EmployeeImages::class;
    }
}
