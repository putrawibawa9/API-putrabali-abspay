<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getByCourse($course_id)
    {
        // Ambil semua nilai siswa yang tergabung dalam course tertentu
        $assessments = DB::table('assessments as a')
            ->join('students as s', 's.id', '=', 'a.student_id')
            ->join('students_courses as sc', 'sc.student_id', '=', 's.id')
            ->join('courses as c', 'c.id', '=', 'sc.course_id')
            ->where('sc.course_id', $course_id)
            ->select(
                'a.id as assessment_id',
                's.id as student_id',
                's.name as student_name',
                'a.subject',
                'a.type',
                'a.score',
                'a.remarks',
                'c.subject as course_subject',
                'c.level as course_level',
                'c.section as course_section'
            )
            ->orderBy('s.name')
            ->get();

        // Kelompokkan berdasarkan siswa (opsional)
        $grouped = $assessments->groupBy('student_id');

        return response()->json([
            'course_id' => $course_id,
            'data' => $grouped
        ]);
    }

    public function indexByCourse($courseId)
    {
        $assessments = Assessment::where('course_id', $courseId)
            ->orderBy('date')
            ->get();

        return response()->json($assessments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // dd($request->all());
    // Validasi input
    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'subject'    => 'required|string|max:100',
        'type'       => 'required|string|max:50',
        'score'      => 'required|integer|min:10|max:100',
        'remarks'    => 'nullable|string',
    ]);

    // Simpan ke database
    $assessment = \App\Models\Assessment::create([
        'student_id' => $validated['student_id'],
        'subject'    => $validated['subject'],
        'type'       => $validated['type'] ?? 'OTHER',
        'score'      => $validated['score'],
        'remarks'    => $validated['remarks'] ?? null,
    ]);

    // return error if validation fails
    if (!$assessment) {
        return response()->json(['message' => 'Assessment gagal ditambahkan'], 500);
    }

    return response()->json([
        'message' => 'Assessment berhasil ditambahkan',
        'data'    => $assessment
    ], 201);
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil data student
        $student = \App\Models\Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student tidak ditemukan'], 404);
        }

        // Ambil semua assessment milik student
        $assessments = Assessment::where('student_id', $id)->orderBy('created_at', 'desc')->get();

        $studentData = $student->toArray();
        $studentData['assessments'] = $assessments;

        return response()->json($studentData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessment->delete();

        return response()->json(['message' => 'Assessment deleted']);
    }
}
