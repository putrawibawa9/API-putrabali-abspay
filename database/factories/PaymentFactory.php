<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => $this->faker->numberBetween(1, 10),
            'course_id' => $this->faker->numberBetween(1, 6),
            'payment_date' => $this->faker->date,
            'payment_amount' => $this->faker->numberBetween(1000, 10000),
            'payment_month' => $this->faker->monthName,
        ];
    }
}
