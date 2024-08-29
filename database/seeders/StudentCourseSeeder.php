<?php

namespace Database\Seeders;

use App\Models\StudentCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentCourse::create([
            'student_id' => 1,
            'course_id' => 6,
            'custom_payment_rate'=> 70000,
        ]);
        StudentCourse::create([
    'student_id' => 2,
    'course_id' => 4,
    'custom_payment_rate' => 80000,
]);

StudentCourse::create([
    'student_id' => 3,
    'course_id' => 2,
    'custom_payment_rate' => 90000,
]);

StudentCourse::create([
    'student_id' => 4,
    'course_id' => 5,
    'custom_payment_rate' => null, // Nullable
]);

StudentCourse::create([
    'student_id' => 5,
    'course_id' => 1,
    'custom_payment_rate' => 85000,
]);

StudentCourse::create([
    'student_id' => 6,
    'course_id' => 3,
    'custom_payment_rate' => null, // Nullable
]);

StudentCourse::create([
    'student_id' => 7,
    'course_id' => 2,
    'custom_payment_rate' => 95000,
]);

StudentCourse::create([
    'student_id' => 8,
    'course_id' => 4,
    'custom_payment_rate' => null, // Nullable
]);

StudentCourse::create([
    'student_id' => 9,
    'course_id' => 5,
    'custom_payment_rate' => 100000,
]);

StudentCourse::create([
    'student_id' => 10,
    'course_id' => 1,
    'custom_payment_rate' => null, // Nullable
]);

StudentCourse::create([
    'student_id' => 11,
    'course_id' => 3,
    'custom_payment_rate' => 90000,
]);

StudentCourse::create([
    'student_id' => 12,
    'course_id' => 2,
    'custom_payment_rate' => null, // Nullable
]);

    }
}
