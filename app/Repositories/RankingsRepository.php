<?php

namespace App\Repositories;

use App\Models\Rankings;
use App\Repositories\BaseRepository;

/**
 * Class RankingsRepository
 * @package App\Repositories
 * @version November 14, 2021, 2:49 am UTC
*/

class RankingsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'RankingRepositoryId',
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
        return Rankings::class;
    }
}
