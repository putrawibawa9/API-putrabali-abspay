<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\StudentCourse;

class StudentCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    $students = Student::all()->map(function ($student) {
        return ['id' => $student->id, 'name' => $student->name];
    });

    $courses = Course::all()->map(function ($course) {
        return ['id' => $course->id, 'name' => $course->name];
    });

    return response()->json(['students' => $students, 'courses' => $courses]);
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

        // check if student is already enrolled in the class
        $studentCourse = StudentCourse::where('student_id', $request->student_id)->where('course_id', $request->course_id)->first();
        if ($studentCourse) {
            return response()->json(['message' => 'Student already enrolled in this class']);
        }
        //enroll student to a class
        $studentCourse = new StudentCourse();
        $studentCourse->student_id = $request->student_id;
        $studentCourse->course_id = $request->course_id;
        $studentCourse->custom_payment_rate = $request->custom_payment_rate;
        $studentCourse->save();
        return response()->json($studentCourse);

    }

    /**
     * Display the specified resource.
     */
   public function show($id)
{
    // Fetch the student with related courses and custom payment rate
    $studentCourses = StudentCourse::with(['course' => function ($query) {
        // Fetch the course details
        $query->select('id', 'name', 'payment_rate');
    }, 'student' => function ($query) {
        // Fetch the student details
        $query->select('id', 'name');
    }])
    ->where('student_id', $id)
    ->get();

    if ($studentCourses->isEmpty()) {
        return response()->json(['error' => 'Student or courses not found'], 404);
    }

    // Get the student's name
    $studentName = $studentCourses->first()->student->name;

    // Transform data to include student name, custom payment rate, and course details
    $studentData = $studentCourses->map(function ($studentCourse) use ($studentName) {
        return [
            'student_name' => $studentName,
            'course_id' => $studentCourse->course_id,
            'course_name' => $studentCourse->course->name,
            'payment_rate' => $studentCourse->course->payment_rate,
            'custom_payment_rate' => $studentCourse->custom_payment_rate,
        ];
    });

    return response()->json($studentData);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentCourse $studentClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentCourse $studentClass)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentCourse $studentClass)
    {
        //
    }

    public function allEnrolledStudents()
    {
        // Fetch all students with their enrolled courses but without duplicates
        $students = StudentCourse::with('student')
            ->select('student_id')
            ->distinct()
            ->get();
            return response()->json($students);
    }
}
