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
            [
                'nis' => "1234567890",
                'name' => "I Gede Putra Wibawa",
                'wa_number' => "081234567890",
                'gender' => "male",
                'school' => "SMPN 1 Denpasar",
                'enroll_date' => "2021-01-01"
            ],
            [
                'nis' => "0987654321",
                'name' => "Ni Luh Putu Ayu",
                'wa_number' => "081234567891",
                'gender' => "female",
                'school' => "SMPN 1 Denpasar",
                'enroll_date' => "2021-01-03"
            ]
        ];
    }
}
