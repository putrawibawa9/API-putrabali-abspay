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
        // Get all students from the database based on their alphabitic order name
        $students = Student::orderBy('name')->get();
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
        $student = Student::where('id', $student->id)->with('courses')->first();
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
}
