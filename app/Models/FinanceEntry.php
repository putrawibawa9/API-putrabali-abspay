<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceEntry extends Model
{
    use HasFactory;

    protected $guarded = [];    

    public function financeCategory()
    {
        return $this->belongsTo(FinanceCategory::class);
    }
}
