<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fine>
 */
class FineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loan_id' => \App\Models\Loan::factory(),
            'value' => $this->faker->randomFloat(2, 0, 50),
            'payment_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['open', 'closed']),
            'observation' => $this->faker->text(),


        ];

    }
}
