<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'stand_for', 'payment_rate', 'type'];
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_classes')
                    ->withPivot('custom_payment_rate')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
