<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\Course;
use App\Models\Meeting;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seed the student Seeder
        $this->call([
            StudentSeeder::class,
            CourseSeeder::class,
            TeacherSeeder::class,
            // PaymentSeeder::class,
            StudentCourseSeeder::class,
            // ScheduleSeeder::class,
            MeetingSeeder::class,
            UserSeeder::class,
            // AbsenceSeeder::class,
            ]
        );
    }
}
