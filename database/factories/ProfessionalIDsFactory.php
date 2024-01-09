<?php

namespace Database\Factories;

use App\Models\ProfessionalIDs;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionalIDsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProfessionalIDs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmployeeId' => $this->faker->word,
        'Entity' => $this->faker->word,
        'EntityId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
