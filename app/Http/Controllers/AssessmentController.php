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
        $data = $request->validate([
            'course_id'  => ['required', 'exists:courses,id'],
            'name'       => ['required', 'string', 'max:100'],
            'type'       => ['required', Rule::in(['UTS','UAS','QUIZ','TASK','PROJECT','OTHER'])],
            'weight'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'max_score'  => ['nullable', 'numeric', 'min:1', 'max:1000'],
            'date'       => ['nullable', 'date'],
            // 'meeting_id' => ['nullable', 'exists:meetings,id'],
            // 'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        $data['max_score'] = $data['max_score'] ?? 100;

        $assessment = Assessment::create($data);

        return response()->json($assessment, 201);
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
     public function destroy($id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessment->delete();

        return response()->json(['message' => 'Assessment deleted']);
    }
}
