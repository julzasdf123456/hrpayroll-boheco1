<?php

namespace Database\Factories;

use App\Models\AttendanceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttendanceData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'BiometricUserId' => $this->faker->word,
        'EmployeeId' => $this->faker->word,
        'UserId' => $this->faker->word,
        'Timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'State' => $this->faker->word,
        'UID' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
