<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\StudentCourse;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\StudentCourseRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
 
    //    get all students latest data first and paginate
        $students = Student::latest()->paginate(20);
        return response()->json($students);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $studentRequest)
{

    // Add a new student to the database
    $latestNis = Student::max('nis');
    $student = new Student();
    $student->nis = $latestNis ? $latestNis + 1 : 1001;
    $student->name = $studentRequest->name;
    $student->wa_number = $studentRequest->wa_number;
    $student->gender = $studentRequest->gender;
    $student->school = $studentRequest->school;
    $student->enroll_date = $studentRequest->enroll_date;
    $student->save();

    // Enroll the student in one or multiple courses
    $courses = $studentRequest->courses; // Array of courses
    foreach ($courses as $course) {
        $studentCourse = new StudentCourse();
        $studentCourse->student_id = $student->id;
        $studentCourse->course_id = $course['course_id'];
        $studentCourse->custom_payment_rate = $course['custom_payment_rate'] ?? null; // Optional
        $studentCourse->save();
    }

//   response the student new id and also the courses that the student enroll
    return response()->json($student->id, 201);
}


    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
    //    check the course that the student enroll
        $student = Student::where('id', $student->id)->with('activeCourses')->first();
        return response()->json($student);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(StudentRequest $request, Student $student)
{
    $validated = $request->validated();
    // dd($student->id);
    // update the student in the database
    $student->update($validated);
    return response()->json($student->id, 201);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // delete a student from the database
        $student->delete();
    }

   public function search(Request $request)
{
 
    $search = $request->query('search'); // Use query() for GET requests

    $students = Student::where('name', 'like', '%' . $search . '%')
        ->orWhere('nis', 'like', '%' . $search . '%')
        ->paginate(20)
        ->appends(['search' => $search]); // Append search term to pagination links

    return response()->json($students);
}


    public function monthlyEnrolledStudent()
    {
        $students = Student::whereMonth('created_at', date('m'))->get();
        $sum = $students->count();
        // return the students data and also the summary of the students
        return response()->json(['sum' => $sum, 'students' => $students]);
    }

    


}


