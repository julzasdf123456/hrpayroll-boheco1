<?php

namespace App\Repositories;

use App\Models\BiometricUsers;
use App\Repositories\BaseRepository;

/**
 * Class BiometricUsersRepository
 * @package App\Repositories
 * @version February 2, 2023, 5:01 am UTC
*/

class BiometricUsersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'UID',
        'Name',
        'UserId',
        'Role',
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
        return BiometricUsers::class;
    }
}
