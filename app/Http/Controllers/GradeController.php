<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Assessment;
use Illuminate\Http\Request;
use App\Models\StudentCourse;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function indexByStudent($studentId)
    {
        $grades = Grade::with(['assessment.course','enrollment.course'])
            ->whereHas('enrollment', fn($q) => $q->where('student_id', $studentId))
            ->orderByDesc('graded_at')
            ->get();

        return response()->json($grades);
    }

      public function indexByCourse($courseId)
    {
        $grades = Grade::with(['assessment','enrollment.student'])
            ->whereHas('assessment', fn($q) => $q->where('course_id', $courseId))
            ->orderBy('assessment_id')
            ->get();

        return response()->json($grades);
    }


     public function finalScoresByStudent($studentId)
    {
        // Ambil semua enrollment murid
        $enrollments = StudentCourse::with('course')
            ->where('student_id', $studentId)
            ->get();

        $result = [];
        foreach ($enrollments as $enr) {
            // Hitung SUM( (score/max_score) * weight ), asumsi total weight = 100
            $rows = DB::table('grades as g')
                ->join('assessments as a', 'a.id', '=', 'g.assessment_id')
                ->where('g.students_courses_id', $enr->id)
                ->selectRaw('a.name, a.type, a.weight, a.max_score, g.score,
                    (g.score / a.max_score) * COALESCE(a.weight,0) as weighted')
                ->get();

            $final = round($rows->sum('weighted'), 2);
            $result[] = [
                'enrollment_id' => $enr->id,
                'course_id'     => $enr->course_id,
                'course'        => $enr->course?->alias ?? null,
                'subject'       => $enr->course?->subject ?? null,
                'components'    => $rows,
                'final_score'   => $final, // 0..100 jika total weight = 100
            ];
        }

        return response()->json($result);
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
    public function bulkStore(Request $request)
    {
        $data = $request->validate([
            'assessment_id' => ['required', 'exists:assessments,id'],
            'graded_by'     => ['nullable', 'exists:users,id'],
            'graded_at'     => ['nullable', 'date'],
            'items'         => ['required', 'array', 'min:1'],
            'items.*.students_courses_id' => ['required', 'exists:students_courses,id'],
            'items.*.score' => ['required', 'numeric', 'min:0'],
            'items.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        $assessment = Assessment::findOrFail($data['assessment_id']);
        $max = $assessment->max_score;

        // Validasi score vs max_score
        foreach ($data['items'] as $i => $row) {
            if ($row['score'] > $max) {
                return response()->json([
                    'message' => "Row #".($i+1)." score exceeds max_score ($max)"
                ], 422);
            }
        }

        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $row) {
                Grade::updateOrCreate(
                    [
                        'students_courses_id' => $row['students_courses_id'],
                        'assessment_id'       => $data['assessment_id'],
                    ],
                    [
                        'score'     => $row['score'],
                        'notes'     => $row['notes'] ?? null,
                        'graded_by' => $data['graded_by'] ?? "null",
                        'graded_at' => $data['graded_at'] ?? now(),
                    ]
                );
            }
        });

        return response()->json(['message' => 'Grades saved/updated'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
