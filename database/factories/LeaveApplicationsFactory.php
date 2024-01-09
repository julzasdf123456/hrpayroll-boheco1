<?php

namespace Database\Factories;

use App\Models\LeaveApplications;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveApplicationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveApplications::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmployeeId' => $this->faker->word,
        'DateFrom' => $this->faker->word,
        'DateTo' => $this->faker->word,
        'NumberOfDays' => $this->faker->randomDigitNotNull,
        'Content' => $this->faker->word,
        'Status' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
