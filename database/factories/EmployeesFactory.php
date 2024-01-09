<?php

namespace Database\Factories;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employees::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'FirstName' => $this->faker->word,
        'MiddleName' => $this->faker->word,
        'LastName' => $this->faker->word,
        'Suffix' => $this->faker->word,
        'Gender' => $this->faker->word,
        'Birthdate' => $this->faker->word,
        'StreetCurrent' => $this->faker->word,
        'BarangayCurrent' => $this->faker->word,
        'TownCurrent' => $this->faker->word,
        'ProvinceCurrent' => $this->faker->word,
        'StreetPermanent' => $this->faker->word,
        'BarangayPermanent' => $this->faker->word,
        'TownPermanent' => $this->faker->word,
        'ProvincePermanent' => $this->faker->word,
        'ContactNumbers' => $this->faker->word,
        'EmailAddress' => $this->faker->word,
        'BloodType' => $this->faker->word,
        'CivilStatus' => $this->faker->word,
        'Religion' => $this->faker->word,
        'Citizenship' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
