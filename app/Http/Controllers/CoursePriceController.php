<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePrice;
use Illuminate\Http\Request;

class CoursePriceController extends Controller
{
    // 🟩 READ – tampilkan semua data
    public function index()
    {
        $prices = CoursePrice::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json($prices);
    }

    // 🟦 CREATE – tambah data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer',
            'year' => 'required|integer|min:2000',
            'month' => 'required|integer|min:1|max:12',
            'price' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        $price = CoursePrice::create($validated);

        return response()->json([
            'message' => 'Course price created successfully!',
            'data' => $price,
        ], 201);
    }

    // 🟨 UPDATE – ubah data tertentu
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'price' => 'sometimes|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        $price = CoursePrice::findOrFail($id);
        $price->update($validated);

        return response()->json([
            'message' => 'Course price updated successfully!',
            'data' => $price,
        ]);
    }

public function yearly($courseId, $year)
{
    // Pastikan course ada
    $course = Course::find($courseId);
    if (!$course) {
        return response()->json([
            'message' => 'Course not found.'
        ], 404);
    }

    $yearlyPrices = CoursePrice::getYearlyPrices($courseId, $year);

    return response()->json([
        'course_id' => $courseId,
        'course_name' => $course->alias ?? 'Unknown',
        'year' => $year,
        'default_payment_rate' => $course->payment_rate,
        'prices' => $yearlyPrices
    ]);
}

}
