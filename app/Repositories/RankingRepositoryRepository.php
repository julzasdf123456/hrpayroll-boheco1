<?php

namespace App\Repositories;

use App\Models\RankingRepository;
use App\Repositories\BaseRepository;

/**
 * Class RankingRepositoryRepository
 * @package App\Repositories
 * @version November 14, 2021, 2:49 am UTC
*/

class RankingRepositoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Type',
        'RankingName',
        'Description',
        'Points',
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
        return RankingRepository::class;
    }
}
