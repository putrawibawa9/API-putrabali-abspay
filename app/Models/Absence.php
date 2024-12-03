<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'students_courses_id',
        'meeting_id',
        'status',
    ];


      // An absence belongs to a student_course
    public function studentCourse()
    {
        return $this->belongsTo(StudentCourse::class, 'students_courses_id');
    }

    // An absence belongs to a meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class)->orderBy('created_at', 'desc');
    }

    // An absence belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
