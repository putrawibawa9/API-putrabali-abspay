<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // add a new schedule to the database
        $schedule = new Schedule();
        $schedule->course_id = $request->course_id;
        $schedule->day = $request->day;
        $schedule->start = $request->start;
        $schedule->end = $request->end;
        $schedule->location = $request->location;
        $schedule->save();
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

  public function getStudentSchedules($nis)
{
    $student = Student::where('nis', $nis)->first();

    if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
    }

    $courses = $student->courses;
    $schedules = [];

    foreach ($courses as $course) {
        $schedules[] = [
            'course_name' => $course->name,  // Include course name
            'schedules' => $course->schedules // Include schedules for this course
        ];
    }

    return response()->json(['schedules' => $schedules]);
}

public function getTeacherSchedule($teacherId)
{
    $teacher = Teacher::find($teacherId);

    if (!$teacher) {
        return response()->json(['message' => 'Teacher not found'], 404);
    }

    $courses = $teacher->courses;
    $schedules = [];

    foreach ($courses as $course) {
        $schedules[] = [
            'course_name' => $course->name,  // Include course name
            'schedules' => $course->schedules // Include schedules for this course
        ];
    }

    return response()->json(['schedules' => $schedules]);
}

}
