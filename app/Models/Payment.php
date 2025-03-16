<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'payment_date',
        'payment_month',
        'type',
        'payment_amount',
    
    ];
    // A payment belongs to a student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A payment belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
