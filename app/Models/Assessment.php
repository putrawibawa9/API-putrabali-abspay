<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Assessment extends Model
{
    protected $fillable = [
        'student_id', 'subject', 'type', 'score', 'remarks'
    ];


    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
   

   

   
}
