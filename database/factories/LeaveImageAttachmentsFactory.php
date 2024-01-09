<?php

namespace Database\Factories;

use App\Models\LeaveImageAttachments;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveImageAttachmentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveImageAttachments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'LeaveId' => $this->faker->word,
        'HexImage' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
