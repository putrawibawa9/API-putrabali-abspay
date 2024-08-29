<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function getAttendances(Request $request)
{
    // Validate the request parameters
    $request->validate([
        'course_id' => 'required|integer',
        'meeting_id' => 'required|integer',
        'status' => 'required|string|in:present,absent'
    ]);

    // Retrieve parameters from the request
    $courseId = $request->input('course_id');
    $meetingId = $request->input('meeting_id');
    $status = $request->input('status');

    // Query to get students who attended a specific class
    $students = DB::table('students')
        ->join('student_courses', 'students.id', '=', 'student_courses.student_id')
        ->join('absences', 'student_courses.id', '=', 'absences.student_course_id')
        ->where('student_courses.course_id', $courseId)
        ->where('absences.meeting_id', $meetingId)
        ->where('absences.status', $status)
        ->select('students.id', 'students.name', 'students.wa_number', 'students.gender', 'students.school')
        ->get();

    // Return the data as JSON response
    return response()->json([
        'success' => true,
        'data' => $students
    ]);
}

    public function store(Request $request){
        // Validate the request data
        $validatedData = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'meeting_id' => 'required|integer|exists:meetings,id',
            'status' => 'required|string|in:present,absent',
        ]);

        // Create the attendance record
        $attendance = Attendance::create([
            'student_id' => $validatedData['student_id'],
            'meeting_id' => $validatedData['meeting_id'],
            'status' => $validatedData['status'],
        ]);

        // Return a response indicating success
        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully',
            'data' => $attendance
        ], 201);
    }
    }


