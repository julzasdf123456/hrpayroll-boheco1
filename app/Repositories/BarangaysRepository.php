<?php

namespace App\Repositories;

use App\Models\Barangays;
use App\Repositories\BaseRepository;

/**
 * Class BarangaysRepository
 * @package App\Repositories
 * @version September 9, 2021, 8:52 am UTC
*/

class BarangaysRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Barangays',
        'TownId',
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
        return Barangays::class;
    }
}
