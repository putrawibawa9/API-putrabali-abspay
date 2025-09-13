<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilySalary extends Model
{
    use HasFactory;

     protected $fillable = ['family_member_id', 'date', 'amount'];

    public function member()
    {
        return $this->belongsTo(FamilyMember::class, 'family_member_id');
    }
}
