<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all students from the database descending by id
        $students = Student::orderBy('id', 'desc')->get();
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
    public function store(Request $request)
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

        return response()->json($student);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
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
    public function update(Request $request, Student $student)
    {
        // update a student in the database
        $student->nis = $request->nis;
        $student->name = $request->name;
        $student->wa_number = $request->wa_number;
        $student->gender = $request->gender;
        $student->school = $request->school;
        $student->enroll_date = $request->enroll_date;
        $student->save();
        return response()->json($student);
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
