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
            'level' => '6',
            'section' => 'A',
            'alias' => '6sra',
            'payment_rate' => 80000,
            'subject' => 'English',
        ]);

        Course::create([
            'level' => '6',
            'section' => 'B',
            'alias' => '6srb',
            'payment_rate' => 80000,
            'subject' => 'English',
        ]);

        Course::create([
            'level' => '6',
            'section' => 'C',
            'alias' => '6src',
            'payment_rate' => 80000,
            'subject' => 'English',
        ]);

        Course::create([
            'level' => '6',
            'section' => 'D',
            'alias' => '6srd',
            'payment_rate' => 80000,
            'subject' => 'English',
        ]);

        Course::create([
            'level' => '7',
            'section' => 'E',
            'alias' => '6sre',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);

        Course::create([
            'level' => '7',
            'section' => 'F',
            'alias' => '6srf',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);

        Course::create([
            'level' => '7',
            'section' => 'G',
            'alias' => '6srg',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);

        Course::create([
            'level' => '7',
            'section' => 'H',
            'alias' => '6srh',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);


    }
}
