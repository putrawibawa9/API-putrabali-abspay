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
        // paginate and latest data first

        $teachers = Teacher::latest()->paginate(20);
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
        $teacher->username = $request->username;
        $teacher->password = bcrypt($request->password);
        $teacher->alias = $request->alias;
        $teacher->save();
        return response(null, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //show teacher by id
        $teacher = Teacher::findOrFail($id);
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
       $validated = $request->validated();
    //    dd($validated);
    // update the student in the database
    $teacher->update($validated);
    return response(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
      
             $teacher->delete();

             return response(null, 204);
    }

      public function search(Request $request)
    {
        $teachers = Teacher::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('alias', 'like', '%' . $request->search . '%')
            ->paginate(5)
            ->appends($request->query());
        return response()->json($teachers);
    }
}
