<?php

namespace App\Repositories;

use App\Models\Messages;
use App\Repositories\BaseRepository;

class MessagesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Sender',
        'Receiver',
        'Message',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Messages::class;
    }
}
