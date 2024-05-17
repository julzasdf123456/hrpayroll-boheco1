<?php

namespace App\Repositories;

use App\Models\MessageHeads;
use App\Repositories\BaseRepository;

class MessageHeadsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'Sender',
        'Receiver',
        'LatestMessage'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MessageHeads::class;
    }
}
