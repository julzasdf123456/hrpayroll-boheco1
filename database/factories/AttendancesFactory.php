<?php

namespace Database\Factories;

use App\Models\Attendances;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendancesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendances::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EmployeeId' => $this->faker->word,
        'MorningTimeIn' => $this->faker->date('Y-m-d H:i:s'),
        'MorningTimeOut' => $this->faker->date('Y-m-d H:i:s'),
        'AfternoonTimeIn' => $this->faker->date('Y-m-d H:i:s'),
        'AfternoonTimeOut' => $this->faker->date('Y-m-d H:i:s'),
        'OTTimeIn' => $this->faker->date('Y-m-d H:i:s'),
        'OTTimeOut' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
