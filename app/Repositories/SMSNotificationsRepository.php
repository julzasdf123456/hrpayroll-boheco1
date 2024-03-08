<?php

namespace App\Repositories;

use App\Models\SMSNotifications;
use App\Repositories\BaseRepository;

class SMSNotificationsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ContactNumber',
        'Message',
        'Status',
        'Source',
        'SourceId',
        'Notes',
        'AIFacilitator'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SMSNotifications::class;
    }
}
