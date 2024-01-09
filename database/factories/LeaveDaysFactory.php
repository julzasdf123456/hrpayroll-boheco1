<?php

namespace Database\Factories;

use App\Models\LeaveDays;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveDaysFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveDays::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'LeaveId' => $this->faker->word,
        'LeaveDate' => $this->faker->word,
        'Longevity' => $this->faker->randomDigitNotNull,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
