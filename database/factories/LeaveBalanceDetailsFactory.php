<?php

namespace Database\Factories;

use App\Models\LeaveBalanceDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveBalanceDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveBalanceDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmployeeId' => $this->faker->word,
        'Method' => $this->faker->word,
        'Days' => $this->faker->word,
        'Details' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
