<?php

namespace App\Repositories;

use App\Models\Overtimes;
use App\Repositories\BaseRepository;

/**
 * Class OvertimesRepository
 * @package App\Repositories
 * @version December 14, 2021, 3:35 am UTC
*/

class OvertimesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'DateOfOT',
        'From',
        'To',
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
        return Overtimes::class;
    }
}
