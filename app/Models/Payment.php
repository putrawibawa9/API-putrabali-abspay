<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // A payment belongs to a student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // A payment belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
