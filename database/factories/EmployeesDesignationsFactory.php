<?php

namespace Database\Factories;

use App\Models\EmployeesDesignations;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeesDesignationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeesDesignations::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmployeeId' => $this->faker->word,
        'Designation' => $this->faker->word,
        'Description' => $this->faker->word,
        'DateStarted' => $this->faker->word,
        'DateEnd' => $this->faker->word,
        'SalaryGrade' => $this->faker->word,
        'SalaryAmount' => $this->faker->word,
        'SalaryAddOns' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
