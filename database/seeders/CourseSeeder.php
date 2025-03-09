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

        Course::create([
            'level' => '8',
            'section' => 'I',
            'alias' => '6sri',
            'payment_rate' => 80000,
            'subject' => 'Math',
        ]);

        Course::create([
            'level' => '8',
            'section' => 'J',
            'alias' => '6srj',
            'payment_rate' => 80000,
            'subject' => 'Math',
        ]);

        Course::create([
            'level' => '8',
            'section' => 'K',
            'alias' => '6srk',
            'payment_rate' => 80000,
            'subject' => 'Math',
        ]);

        Course::create([
            'level' => '8',
            'section' => 'L',
            'alias' => '6srl',
            'payment_rate' => 80000,
            'subject' => 'Math',
        ]);

        Course::create([
            'level' => '9',
            'section' => 'M',
            'alias' => '6srm',
            'payment_rate' => 80000,
            'subject' => 'Science',
        ]);

        Course::create([
            'level' => '9',
            'section' => 'N',
            'alias' => '6srn',
            'payment_rate' => 80000,
            'subject' => 'Science',
        ]);

        Course::create([
            'level' => '9',
            'section' => 'O',
            'alias' => '6sro',
            'payment_rate' => 80000,
            'subject' => 'Science',
        ]);

        Course::create([
            'level' => '9',
            'section' => 'P',
            'alias' => '6srp',
            'payment_rate' => 80000,
            'subject' => 'Science',
        ]);

        Course::create([
            'level' => '10',
            'section' => 'Q',
            'alias' => '6srq',
            'payment_rate' => 80000,
            'subject' => 'Social',
        ]);

        Course::create([
            'level' => '10',
            'section' => 'R',
            'alias' => '6srr',
            'payment_rate' => 80000,
            'subject' => 'Social',
        ]);

        Course::create([
            'level' => '10',
            'section' => 'S',
            'alias' => '6srs',
            'payment_rate' => 80000,
            'subject' => 'Social',
        ]);

        Course::create([
            'level' => '10',
            'section' => 'T',
            'alias' => '6srt',
            'payment_rate' => 80000,
            'subject' => 'Social',
        ]);

        Course::create([
            'level' => '11',
            'section' => 'U',
            'alias' => '6sru',
            'payment_rate' => 80000,
            'subject' => 'History',
        ]);

        Course::create([
            'level' => '11',
            'section' => 'V',
            'alias' => '6srv',
            'payment_rate' => 80000,
            'subject' => 'History',
        ]);


    }
}
