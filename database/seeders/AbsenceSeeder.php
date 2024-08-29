<?php

namespace Database\Seeders;

use App\Models\Absence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Absence::create([
            'students_courses_id' => 1,
            'meeting_id' => 6,
            'status' => 'present',
            'teacher_id' => '5',
        ]);
        Absence::create([
    'students_courses_id' => 2,
    'meeting_id' => 1,
    'status' => 'absent',
    'teacher_id' => 2,
]);

        Absence::create([
            'students_courses_id' => 3,
            'meeting_id' => 1,
            'status' => 'present',
            'teacher_id' => 3,
        ]);

        Absence::create([
            'students_courses_id' => 4,
            'meeting_id' => 1,
            'status' => 'absent',
            'teacher_id' => 4,
        ]);

        Absence::create([
            'students_courses_id' => 5,
            'meeting_id' => 1,
            'status' => 'present',
            'teacher_id' => 1,
        ]);

        Absence::create([
            'students_courses_id' => 6,
            'meeting_id' => 1,
            'status' => 'absent',
            'teacher_id' => 2,
        ]);

        Absence::create([
            'students_courses_id' => 7,
            'meeting_id' => 1,
            'status' => 'present',
            'teacher_id' => 3,
        ]);

        Absence::create([
            'students_courses_id' => 8,
            'meeting_id' => 8,
            'status' => 'absent',
            'teacher_id' => 4,
        ]);

        Absence::create([
            'students_courses_id' => 9,
            'meeting_id' => 9,
            'status' => 'present',
            'teacher_id' => 5,
        ]);

        Absence::create([
            'students_courses_id' => 10,
            'meeting_id' => 10,
            'status' => 'absent',
            'teacher_id' => 1,
        ]);

        Absence::create([
            'students_courses_id' => 11,
            'meeting_id' => 6,
            'status' => 'present',
            'teacher_id' => 2,
        ]);

    }
}
