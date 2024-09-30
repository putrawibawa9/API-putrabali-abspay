<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::all();
        return response()->json($teachers);
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
    public function store(TeacherRequest $request)
    {
        $teacher = new Teacher();
        $teacher->name = $request->name;
        $teacher->alias = $request->alias;
        $teacher->save();
        return response(null, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($alias)
    {
        // check the name based on aliases
        $teacher = Teacher::where('alias', $alias)->first();
        return response()->json($teacher);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherRequest $request, Teacher $teacher)
    {
        // Update teacher
        $teacher->name = $request->name;
        $teacher->alias = $request->alias;
        $teacher->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($alias)
    {
        // delete a teacher from the database
        $teacher = Teacher::where('alias', $alias)->first();
        $teacher->delete();
        
    }
}
