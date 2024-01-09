<?php

namespace App\Repositories;

use App\Models\Towns;
use App\Repositories\BaseRepository;

/**
 * Class TownsRepository
 * @package App\Repositories
 * @version September 9, 2021, 8:51 am UTC
*/

class TownsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Town',
        'District',
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
        return Towns::class;
    }
}
