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

 public function setMonthly(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'year' => 'required|integer|min:2000',
            'month' => 'required|integer|min:1|max:12',
            'price' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        // cek apakah sudah ada data untuk bulan & tahun tsb
        $existing = CoursePrice::where('course_id', $validated['course_id'])
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->first();

        if ($existing) {
            // UPDATE
            $existing->update([
                'price' => $validated['price'],
                'note'  => $validated['note'] ?? $existing->note,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Harga bulan ini berhasil diupdate.',
                'action'  => 'updated',
                'data'    => $existing
            ]);
        }

        // CREATE BARU
        $new = CoursePrice::create([
            'course_id' => $validated['course_id'],
            'year'      => $validated['year'],
            'month'     => $validated['month'],
            'price'     => $validated['price'],
            'note'      => $validated['note'],
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Harga bulan ini berhasil ditambahkan.',
            'action'  => 'created',
            'data'    => $new
        ], 201);
    }

}
