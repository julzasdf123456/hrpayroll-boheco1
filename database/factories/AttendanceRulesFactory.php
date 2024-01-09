<?php

namespace Database\Factories;

use App\Models\AttendanceRules;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceRulesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttendanceRules::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'MorningTimeInStart' => $this->faker->word,
        'MorningTimeInEnd' => $this->faker->word,
        'MorningTimeOutStart' => $this->faker->word,
        'MorningTimeOutEnd' => $this->faker->word,
        'AfternoonTimeInStart' => $this->faker->word,
        'AfternoonTimeInEnd' => $this->faker->word,
        'AfternoonTimeOutStart' => $this->faker->word,
        'AfternoonTimeOutEnd' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
