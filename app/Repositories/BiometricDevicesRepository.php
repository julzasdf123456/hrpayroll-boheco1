<?php

namespace App\Repositories;

use App\Models\BiometricDevices;
use App\Repositories\BaseRepository;

class BiometricDevicesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'IPAddress',
        'Brand',
        'Office',
        'Status',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return BiometricDevices::class;
    }
}
