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
            'level' => '4',
            'section' => 'E',
            'alias' => '4bsre',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);

        Course::create([
            'level' => '6',
            'section' => 'f',
            'alias' => '6srf',
            'payment_rate' => 80000,
            'subject' => 'English',
        ]);

        Course::create([
            'level' => '6',
            'section' => 'G',
            'alias' => '6bsrg',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);

        Course::create([
            'level' => '6',
            'section' => 'H',
            'alias' => '6srh',
            'payment_rate' => 80000,
            'subject' => 'English',
        ]);

        Course::create([
            'level' => '6',
            'section' => 'I',
            'alias' => '6bsri',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);

        Course::create([
            'level' => '3',
            'section' => 'J',
            'alias' => '3bsrj',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);

        Course::create([
            'level' => '2',
            'section' => 'a',
            'alias' => '2bsra',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);

        Course::create([
            'level' => '2',
            'section' => 'b',
            'alias' => '2bsrb',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);
        Course::create([
            'level' => '2',
            'section' => 'c',
            'alias' => '2bsrc',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);
        Course::create([
            'level' => '2',
            'section' => 'd',
            'alias' => '2bsrd',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);
        Course::create([
            'level' => '3',
            'section' => 'e',
            'alias' => '3bsre',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);
        Course::create([
            'level' => '3',
            'section' => 'f',
            'alias' => '3bsrf',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);
        Course::create([
            'level' => '3',
            'section' => 'g',
            'alias' => '3bsrg',
            'payment_rate' => 80000,
            'subject' => 'Mapel',
        ]);


    }
}
