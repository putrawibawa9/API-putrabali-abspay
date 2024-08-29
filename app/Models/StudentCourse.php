<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    use HasFactory;

    protected $table = 'students_courses';

  // A student_course belongs to a student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // A student_course belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // A student_course has many absences
    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}
