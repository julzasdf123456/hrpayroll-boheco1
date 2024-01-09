<?php

namespace App\Repositories;

use App\Models\LeaveImageAttachments;
use App\Repositories\BaseRepository;

/**
 * Class LeaveImageAttachmentsRepository
 * @package App\Repositories
 * @version February 6, 2023, 9:10 am UTC
*/

class LeaveImageAttachmentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'LeaveId',
        'HexImage'
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
        return LeaveImageAttachments::class;
    }
}
