<?php

namespace Database\Factories;

use App\Models\Overtimes;
use Illuminate\Database\Eloquent\Factories\Factory;

class OvertimesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Overtimes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmployeeId' => $this->faker->word,
        'DateOfOT' => $this->faker->word,
        'From' => $this->faker->word,
        'To' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
