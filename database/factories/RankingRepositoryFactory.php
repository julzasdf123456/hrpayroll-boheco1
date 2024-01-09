<?php

namespace Database\Factories;

use App\Models\RankingRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class RankingRepositoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RankingRepository::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Type' => $this->faker->word,
        'RankingName' => $this->faker->word,
        'Description' => $this->faker->word,
        'Points' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
