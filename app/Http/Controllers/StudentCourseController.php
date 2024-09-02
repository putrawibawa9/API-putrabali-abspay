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
    public function show(StudentCourse $studentCourse)
    {
        //show specific student class
        $students = Student::all();
        return response()->json($students);
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
}
