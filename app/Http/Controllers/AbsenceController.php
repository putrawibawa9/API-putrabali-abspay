<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsenceRequest;
use App\Models\Absence;
use App\Models\Meeting;
use Illuminate\Http\Request;
use App\Http\Resources\MeetingResource;
use Illuminate\Support\Facades\Validator;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meeting = Meeting::with('course', 'teacher')->get();
        return MeetingResource::collection($meeting);
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
public function store(AbsenceRequest $request)
{
    // create a new meeting record
    $meeting = Meeting::create([
        'course_id' => $request->course_id,
        'day' => $request->day,
        'date' => $request->date,
        'time' => $request->time,
        'teacher_id' => $request->teacher_id,
    ]);
    $absences = [];
    foreach ($request->attendances as $attendance) {
        $absences[] = Absence::create([
            'students_courses_id' => $attendance['students_courses_id'],
            'meeting_id' => $meeting->id,
            'status' => $attendance['status'],
        ]);
    }

   return response(null, 201);
}

    /**
     * Display the specified resource.
     */
    public function show($meeting)
    {
$meeting = Meeting::with('course.studentsCourses.student')->find($meeting);
        return response()->json($meeting);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absence $absence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absence $absence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absence $absence)
    {
        //
    }
}
