<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function search(Request $request)
{
    // Initialize a query builder for the Course model
    $query = Course::query();

    // Add conditions based on the request input, only if the value is not null
    if ($request->filled('level')) {
        $query->where('level', $request->input('level'));
    }

    if ($request->filled('section')) {
        $query->where('section', $request->input('section'));
    }

    if ($request->filled('subject')) {
        $query->where('subject', $request->input('subject'));
    }

    // Retrieve the filtered results
    $courses = $query->get();

    // Return 404 if no course is found
    if ($courses->isEmpty()) {
        return response(null, 404);
    }
    
    // Return the results as a JSON response
    return response()->json($courses);
}



    public function index()
    {
        //add new course
        $courses = Course::all();
        return response()->json($courses);
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
    public function store(CourseRequest $request)
    {
        // Add new course to the database
        $course = new Course();
        $course->level = $request->level;
        $course->section = $request->section;
        $course->subject = $request->subject;
        $course->alias = $request->alias;
        $course->payment_rate = $request->payment_rate;
        $course->save();

        return response(null, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        
        // check if the course is available
        if (!$course) {
            return response(null, 404);
        }
        // check the course and its student if available
        $course = Course::where('id', $course->id)->with('students')->first();
        return response()->json($course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, Course $course)
    {
        // update a course in the database
        $course->name = $request->name;
        $course->alias = $request->alias;
        $course->payment_rate = $request->payment_rate;
        $course->type = $request->type;
        $course->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        // delete a course from the database
        $course->delete();
    }

 
}
