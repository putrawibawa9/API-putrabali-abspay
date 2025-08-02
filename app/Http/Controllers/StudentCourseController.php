<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\StudentCourse;
use App\Http\Requests\StudentCourseRequest;
use Illuminate\Validation\ValidationException;

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
    $student = Student::findOrFail($request->student_id);
    $responses = [];

    // Count current active courses
    $currentActiveCount = $student->courses()->wherePivot('is_active', true)->count();

    // Defensive: check if 'courses' exists and is an array
    if (!is_array($request->courses) || empty($request->courses)) {
        return response()->json(['error' => 'Invalid or empty courses data.'], 422);
    }

    foreach ($request->courses as $courseData) {
        // If limit reached, block
       

        // Defensive: course_id must exist
        if (empty($courseData['course_id'])) {
            $responses[] = [
                'course_id' => null,
                'status' => 'course_id missing in request',
            ];
            continue;
        }

        $course = Course::find($courseData['course_id']);
        if (!$course) {
            $responses[] = [
                'course_id' => $courseData['course_id'],
                'status' => 'course not found',
            ];
            continue;
        }

        // Check if previously enrolled but inactive
        $studentCourse = StudentCourse::where('student_id', $request->student_id)
            ->where('course_id', $courseData['course_id'])
            ->where('is_active', false)
            ->first();

        if ($studentCourse) {
            $studentCourse->is_active = true;
            $studentCourse->custom_payment_rate = $courseData['custom_payment_rate'] ?? null;
            $studentCourse->save();
            $currentActiveCount++; // Increase count
            $responses[] = [
                'course_id' => $courseData['course_id'],
                'status' => 're-enrolled'
            ];
            continue;
        }

        // Check for same type already enrolled
        $existingClasses = $student->courses()
            ->where('subject', $course->type)
            ->wherePivot('is_active', true)
            ->count();

        if ($existingClasses >= 1) {
            $responses[] = [
                'course_id' => $courseData['course_id'],
                'status' => "already enrolled in a $course->type class"
            ];
            continue;
        }

        // Enroll student
        StudentCourse::create([
            'student_id' => $request->student_id,
            'course_id' => $courseData['course_id'],
            'custom_payment_rate' => $courseData['custom_payment_rate'] ?? null,
            'is_active' => true,
        ]);

        $currentActiveCount++; // Increase count
        $responses[] = [
            'course_id' => $courseData['course_id'],
            'status' => 'enrolled'
        ];
    }

    return response()->json($responses, 201);
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
    public function update(Request $request)
    {   
        dd($request->all());
       $course = Course::findOrFail($request->course_id);
        $student = StudentCourse::findOrFail($request->student_id);
        
        return response(null, 201);
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


public function changeCustomPaymentRate(Request $request, $id)
{
    
    $studentCourse = StudentCourse::findOrFail($id);
    $studentCourse->custom_payment_rate = $request->custom_payment_rate;
    $studentCourse->save();

    return response()->json(['message' => 'Custom payment rate updated successfully.']);
}
}
