<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'year',
        'month',
        'price',
        'note',
    ];


    public static function getYearlyPrices($courseId, $year)
{
    // Ambil harga khusus per bulan
    $specials = self::where('course_id', $courseId)
        ->where('year', $year)
        ->get()
        ->keyBy('month'); // supaya mudah diakses by month

    // Ambil harga default dari courses
    $defaultPrice = \App\Models\Course::where('id', $courseId)->value('payment_rate') ?? 0;

    // Buat list 12 bulan
    $months = [];
    for ($m = 1; $m <= 12; $m++) {
        $months[] = [
            'month' => $m,
            'price' => $specials[$m]->price ?? $defaultPrice,
            'source' => isset($specials[$m]) ? 'custom' : 'default',
            'note' => $specials[$m]->note ?? null,
        ];
    }

    return $months;
}

}
