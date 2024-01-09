<?php

namespace Database\Factories;

use App\Models\BiometricUsers;
use Illuminate\Database\Eloquent\Factories\Factory;

class BiometricUsersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BiometricUsers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'UID' => $this->faker->word,
        'Name' => $this->faker->word,
        'UserId' => $this->faker->word,
        'Role' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
