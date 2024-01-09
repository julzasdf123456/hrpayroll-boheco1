<?php

namespace App\Repositories;

use App\Models\Notifications;
use App\Repositories\BaseRepository;

/**
 * Class NotificationsRepository
 * @package App\Repositories
 * @version November 21, 2021, 7:03 am UTC
*/

class NotificationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EmployeeId',
        'Type',
        'Content',
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
        return Notifications::class;
    }
}
