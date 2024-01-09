<?php

namespace Database\Factories;

use App\Models\LeaveSignatories;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveSignatoriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveSignatories::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'LeaveId' => $this->faker->word,
        'EmployeeId' => $this->faker->word,
        'Rank' => $this->faker->randomDigitNotNull,
        'Status' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
