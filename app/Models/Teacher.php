<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'alias', 'username', 'password', 'instagram'];

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'default_teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
