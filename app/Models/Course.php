<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'alias', 'payment_rate', 'type'];
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    // One course has many student enrollments
    public function studentsCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }

    // One course has many payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
