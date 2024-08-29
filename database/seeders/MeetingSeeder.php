<?php

namespace Database\Seeders;

use App\Models\Meeting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Meeting::create([
           'course_id' => 1,
           'day' => 'Monday',
           'date' => '2021-01-01',
           'time' => '08:00',
           'teacher_id' => 1,
        ]);
        Meeting::create([
    'course_id' => 2,
    'day' => 'Tuesday',
    'date' => '2021-02-01',
    'time' => '09:00',
    'teacher_id' => 2,
]);

Meeting::create([
    'course_id' => 3,
    'day' => 'Wednesday',
    'date' => '2021-03-01',
    'time' => '10:00',
    'teacher_id' => 3,
]);

Meeting::create([
    'course_id' => 4,
    'day' => 'Thursday',
    'date' => '2021-04-01',
    'time' => '11:00',
    'teacher_id' => 4,
]);

Meeting::create([
    'course_id' => 5,
    'day' => 'Friday',
    'date' => '2021-05-01',
    'time' => '12:00',
    'teacher_id' => 5,
]);

Meeting::create([
    'course_id' => 1,
    'day' => 'Monday',
    'date' => '2021-06-01',
    'time' => '13:00',
    'teacher_id' => 1,
]);

Meeting::create([
    'course_id' => 2,
    'day' => 'Tuesday',
    'date' => '2021-07-01',
    'time' => '14:00',
    'teacher_id' => 2,
]);

Meeting::create([
    'course_id' => 3,
    'day' => 'Wednesday',
    'date' => '2021-08-01',
    'time' => '15:00',
    'teacher_id' => 3,
]);

Meeting::create([
    'course_id' => 4,
    'day' => 'Thursday',
    'date' => '2021-09-01',
    'time' => '16:00',
    'teacher_id' => 4,
]);

Meeting::create([
    'course_id' => 5,
    'day' => 'Friday',
    'date' => '2021-10-01',
    'time' => '17:00',
    'teacher_id' => 5,
]);

Meeting::create([
    'course_id' => 1,
    'day' => 'Monday',
    'date' => '2021-11-01',
    'time' => '08:00',
    'teacher_id' => 1,
]);

    }
}
