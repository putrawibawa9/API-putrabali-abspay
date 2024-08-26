<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentClass;
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
        Student::factory(10)->create();
        Course::factory(6)->create();
        Payment::factory(20)->create();
        StudentClass::factory(10)->create();
    }
}
