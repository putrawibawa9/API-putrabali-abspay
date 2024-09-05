<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::create([
             'course_id' => 1,
            'day' => 'Monday',
            'start' => '14:30:00',
            'end' => '16:30:00',
            'location' => 'PB1',
        ]);
        Schedule::create([
            'course_id' => 2,
            'day' => 'Tuesday',
            'start' => '10:30:00',
            'end' => '12:30:00',
            'location' => 'PB2',
        ]);
        Schedule::create([
            'course_id' => 1,
            'day' => 'Wednesday',
            'start' => '08:30:00',
            'end' => '10:30:00',
            'location' => 'PB1',
        ]);
        Schedule::create([
            'course_id' => 3,
            'day' => 'Thursday',
            'start' => '14:30:00',
            'end' => '16:30:00',
            'location' => 'PB2',
        ]);
        Schedule::create([
            'course_id' => 2,
            'day' => 'Friday',
            'start' => '10:30:00',
            'end' => '12:30:00',
            'location' => 'PB1',
        ]);
        Schedule::create([
            'course_id' => 3,
            'day' => 'Saturday',
            'start' => '08:30:00',
            'end' => '10:30:00',
            'location' => 'PB2',
        ]);
    }
}
