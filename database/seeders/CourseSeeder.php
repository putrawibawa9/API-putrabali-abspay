<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'name' => '6 A English',
            'alias' => '6sra',
            'payment_rate' => 80000,
            'type' => 'English',
        ]);

        Course::create([
            'name' => '6 B Mapel',
            'alias' => '6bsrb',
            'payment_rate' => 100000,
            'type' => 'Mapel',
        ]);

        Course::create([
            'name' => '6 A Mapel',
            'alias' => '6bsra',
            'payment_rate' => 100000,
            'type' => 'Mapel',
        ]);

        Course::create([
    'name' => '7 B Bahasa Inggris',
    'alias' => '7srb',
    'payment_rate' => 120000,
    'type' => 'English',
]);

Course::create([
    'name' => '8 C Mapel',
    'alias' => '8bsrc',
    'payment_rate' => 130000,
    'type' => 'Mapel',
]);

Course::create([
    'name' => '9 A Mapel',
    'alias' => '9bsra',
    'payment_rate' => 140000,
    'type' => 'Mapel',
]);



    }
}
