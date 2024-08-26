<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
               'nis' => $this->faker->unique()->numberBetween(100000, 999999),
                'name' => $this->faker->name,
                'wa_number' => $this->faker->phoneNumber,
                'gender' => $this->faker->randomElement(['Male', 'Female']),
                'school' => $this->faker->word,
                'enroll_date' => $this->faker->date,
        ];
    }
}
