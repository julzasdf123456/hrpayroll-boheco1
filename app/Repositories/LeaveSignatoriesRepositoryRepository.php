<?php

namespace App\Repositories;

use App\Models\LeaveSignatoriesRepository;
use App\Repositories\BaseRepository;

/**
 * Class LeaveSignatoriesRepositoryRepository
 * @package App\Repositories
 * @version November 21, 2021, 7:40 am UTC
*/

class LeaveSignatoriesRepositoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'UserId'
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
        return LeaveSignatoriesRepository::class;
    }
}
