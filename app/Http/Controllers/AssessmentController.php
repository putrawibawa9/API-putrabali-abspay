<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

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
        'score'      => 'required|integer|min:0|max:10',
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
