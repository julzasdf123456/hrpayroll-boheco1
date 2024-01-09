<?php

namespace Database\Factories;

use App\Models\PayrollSchedules;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollSchedulesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PayrollSchedules::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Name' => $this->faker->word,
        'StartTime' => $this->faker->word,
        'BreakStart' => $this->faker->word,
        'BreakEnd' => $this->faker->word,
        'EndTime' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
