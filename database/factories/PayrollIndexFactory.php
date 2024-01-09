<?php

namespace Database\Factories;

use App\Models\PayrollIndex;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollIndexFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PayrollIndex::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'DateFrom' => $this->faker->word,
        'DateTo' => $this->faker->word,
        'EmployeeType' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
