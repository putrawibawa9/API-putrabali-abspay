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

    public function classes()
    {
        return $this->belongsToMany(Course::class, 'student_classes')
                    ->withPivot('custom_payment_rate')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
