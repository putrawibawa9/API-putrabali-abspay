<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'name',
        'wa_number',
        'gender',
        'school',
        'enroll_date',
    ];

     // One student has many student enrollments
    public function studentsCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }

    // One student has many payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

     public function courses()
{
    return $this->belongsToMany(Course::class, 'students_courses') // Pivot table name
                ->withPivot('custom_payment_rate') // Specify the pivot field
                ->withTimestamps(); // If your pivot table includes timestamps
}


public function activeCourses()
{
    return $this->belongsToMany(Course::class, 'students_courses', 'student_id', 'course_id')
                ->wherePivot('is_active', true)
                ->withPivot('id','custom_payment_rate'); // Include the custom_payment_rate
}




}
