<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

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
    public function store(StudentRequest $request)
    {
        // add a new student to the database
        $latestNis = Student::max('nis');
        $student = new Student();
        $student->nis = $latestNis ? $latestNis + 1 : 1001;
        $student->name = $request->name;
        $student->wa_number = $request->wa_number;
        $student->gender = $request->gender;
        $student->school = $request->school;
        $student->enroll_date = $request->enroll_date;
        $student->save();

        return response(null, 201);
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
    // update the student in the database
    $student->update($validated);
    return response(null, 204);
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
        $students = Student::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('nis', 'like', '%' . $request->search . '%')
            ->paginate(5);
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


