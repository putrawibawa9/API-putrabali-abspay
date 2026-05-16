<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_id',
        'course_id',
        'teacher_id',
        'original_teacher_id',
        'day',
        'date',
        'time',
        'end_time',
        'location',
        'lesson_plan',
        'is_canceled',
        'change_note',
    ];

    protected $casts = [
        'date' => 'date',
        'is_canceled' => 'boolean',
    ];

     // A meeting belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // A meeting belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function originalTeacher()
    {
        return $this->belongsTo(Teacher::class, 'original_teacher_id');
    }

    // A meeting has many absences
    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

 
}
