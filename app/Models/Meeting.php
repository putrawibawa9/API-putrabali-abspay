<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'teacher_id', 'day', 'date', 'time'];

     // A meeting belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // A meeting belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // A meeting has many absences
    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

 
}
