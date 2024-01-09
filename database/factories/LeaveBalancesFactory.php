<?php

namespace Database\Factories;

use App\Models\LeaveBalances;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveBalancesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveBalances::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmployeeId' => $this->faker->word,
        'Vacation' => $this->faker->word,
        'Sick' => $this->faker->word,
        'Special' => $this->faker->word,
        'Maternity' => $this->faker->word,
        'MaternityForSoloMother' => $this->faker->word,
        'Paternity' => $this->faker->word,
        'SoloParent' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
