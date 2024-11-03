<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Fine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // data que foi emprestado
        // data que era para ser devolvido
        // data que foi devolvido
        $loan_date = $this->faker->dateTimeBetween('-1 month', 'now');
        $return_date = $this->faker->dateTimeBetween($loan_date, '+30 days');

        $devolution_date = null;

        $status = $this->faker->randomElement(['open', 'closed']);
        if ($status === 'closed') {
            $devolution_date = $this->faker->dateTimeBetween($loan_date, Carbon::instance($return_date)->addDays(30));
        }

        $delivery_status = $devolution_date > $return_date ? 'late' : 'ok';


        return [
            'book_id' => Book::factory(),
            'user_id' => User::factory(),
            'loan_date' => $loan_date,
            'return_date' => $return_date,
            'devolution_date' => $devolution_date,
            'status' => $status,
            'delivery_status' => $delivery_status,
            'observation' => $this->faker->text(),

        ];

    }
}
