<?php

namespace Database\Factories;

use App\Models\PayrollDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PayrollDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'PayrolIndexId' => $this->faker->word,
        'EmployeeId' => $this->faker->word,
        'GrossSalary' => $this->faker->word,
        'TotalDeductions' => $this->faker->word,
        'AddOns' => $this->faker->word,
        'Vat' => $this->faker->word,
        'NetSalary' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
