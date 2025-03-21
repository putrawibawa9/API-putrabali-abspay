<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentCourseRequest;
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
    public function store(StudentCourseRequest $request)
    {

        // check if the student is an re-enrolling student
        $studentCourse = StudentCourse::where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->where('is_active', false)
            ->first();
        // dd($studentCourse);
        if ($studentCourse) {
            $studentCourse->is_active = true;
            $studentCourse->custom_payment_rate = $request->custom_payment_rate;
            $studentCourse->save();
            return response(null, 201);
        }
       // Find the student by ID
    $student = Student::findOrFail($request->student_id);

    // Get the class they are trying to enroll in
    $course = Course::findOrFail($request->course_id);

    $existingClasses = $student->courses()->where('subject', $course->type)->count();

    if ($existingClasses >= 1) {
        return response()->json([
            'message' => "The student is already enrolled in a $course->type class and cannot enroll in another one."
        ], 422);
    }
        $studentCourse = new StudentCourse();
        $studentCourse->student_id = $request->student_id;
        $studentCourse->course_id = $request->course_id;
        $studentCourse->custom_payment_rate = $request->custom_payment_rate;
        $studentCourse->save();
        return response(null, 201);

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
    public function destroy(StudentCourse $id)
    {
        
   
    $id->is_active = false;
    $id->save();
    return response(null, 204);
    }

    public function getStudentsWithActiveCourse()
{
    $students = Student::with('activeCourses')->latest()->paginate(20);

    return response()->json($students);
}

}
