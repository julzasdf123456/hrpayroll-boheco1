<?php

namespace App\Repositories;

use App\Models\Positions;
use App\Repositories\BaseRepository;

/**
 * Class PositionsRepository
 * @package App\Repositories
 * @version January 31, 2023, 3:13 am UTC
*/

class PositionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Position',
        'Description',
        'Level',
        'ParentPositionId',
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
        return Positions::class;
    }
}
