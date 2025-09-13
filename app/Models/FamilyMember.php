<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

       protected $fillable = ['name', 'role', 'daily_salary', 'is_active'];

    public function salaries()
    {
        return $this->hasMany(FamilySalary::class);
    }
}
