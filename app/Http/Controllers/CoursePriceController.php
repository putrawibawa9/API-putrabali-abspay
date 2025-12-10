<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\CoursePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


public function getMonthlyCoursePricebyStudent($studentId)
{
   
    $student = Student::find($studentId);

    if (!$student) {
        return response()->json([
            'message' => 'Student not found.'
        ], 404);
    }

    $currentYear = now()->year;
    $currentMonth = now()->month;

    // Ambil semua kelas aktif yang diikuti murid
    $activeCourses = DB::table('students_courses')
        ->join('courses', 'students_courses.course_id', '=', 'courses.id')
        ->where('students_courses.student_id', $studentId)
        ->where('students_courses.is_active', true)
        ->select('courses.id', 'courses.alias', 'courses.payment_rate')
        ->get();

    $result = [];
    $totalPayment = 0;

    foreach ($activeCourses as $course) {

        // Cek apakah ada override harga di course_prices
        $overridePrice = DB::table('course_prices')
            ->where('course_id', $course->id)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->value('price');

        $finalPrice = $overridePrice ?? $course->payment_rate;

        $result[] = [
            'course_id' => $course->id,
            'course_alias' => $course->alias,
            'default_rate' => $course->payment_rate,
            'override_price' => $overridePrice,
            'final_price_this_month' => $finalPrice,
        ];

        $totalPayment += $finalPrice;
    }
    // dd( $result);

    return response()->json([
        'student_id' => $student->id,
        'student_name' => $student->name,
        'month' => $currentMonth,
        'year' => $currentYear,
        'courses' => $result,
        'total_payment' => $totalPayment
    ]);
}


}
