<?php

namespace Database\Factories;

use App\Models\LeaveSignatoriesRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveSignatoriesRepositoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveSignatoriesRepository::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'UserId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
