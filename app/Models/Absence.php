<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = ['student_course_id', 'day', 'date', 'time'];


      // An absence belongs to a student_course
    public function studentCourse()
    {
        return $this->belongsTo(StudentCourse::class);
    }

    // An absence belongs to a meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    // An absence belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
