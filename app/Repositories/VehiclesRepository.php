<?php

namespace App\Repositories;

use App\Models\Vehicles;
use App\Repositories\BaseRepository;

class VehiclesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'VehicleName',
        'PlateNumber',
        'Brand',
        'Model',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Vehicles::class;
    }
}
