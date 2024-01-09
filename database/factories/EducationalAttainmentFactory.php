<?php

namespace Database\Factories;

use App\Models\EducationalAttainment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EducationalAttainmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EducationalAttainment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmployeeId' => $this->faker->word,
        'Type' => $this->faker->word,
        'Major' => $this->faker->word,
        'School' => $this->faker->word,
        'SchoolYear' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
