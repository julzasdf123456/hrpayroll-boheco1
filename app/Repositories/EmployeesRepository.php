<?php

namespace App\Repositories;

use App\Models\Employees;
use App\Repositories\BaseRepository;

/**
 * Class EmployeesRepository
 * @package App\Repositories
 * @version August 7, 2021, 11:52 am UTC
*/

class EmployeesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'Gender',
        'Birthdate',
        'StreetCurrent',
        'BarangayCurrent',
        'TownCurrent',
        'ProvinceCurrent',
        'StreetPermanent',
        'BarangayPermanent',
        'TownPermanent',
        'ProvincePermanent',
        'ContactNumbers',
        'EmailAddress',
        'BloodType',
        'CivilStatus',
        'Religion',
        'Citizenship'
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
        return Employees::class;
    }
}
