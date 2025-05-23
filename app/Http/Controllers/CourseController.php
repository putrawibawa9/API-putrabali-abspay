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
    $courses = $query->paginate(10)
        ->appends($request->query());
    
    // Return 404 and a message if no results are found
    if ($courses->isEmpty()) {
        return response()->json(['message' => 'No courses found'], 404);
    }
   
    
    // Return the results as a JSON response
    return response()->json($courses);
}



   public function index()
{
    
  $courses = Course::latest()->paginate(20);

  return response()->json($courses);
}


public function courseFilter(Request $request)
{
    $subject = $request->subject;

    $courses = Course::where('subject', $subject)->get();

    // Sort custom: numerik dulu, lalu huruf
    $sorted = $courses->sortBy(function ($course) {
        // Misal field level = "1a", "2b", dsb
        preg_match('/^(\d+)([a-zA-Z]*)$/', $course->level, $matches);
        $number = isset($matches[1]) ? intval($matches[1]) : 0;
        $letter = isset($matches[2]) ? $matches[2] : '';
        return [$number, $letter];
    })->values(); // Reset index

    return response()->json($sorted);
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
    // This null check is unnecessary because Laravel's route model binding already 404s if the model isn't found.
    // But if you really want to keep it:
    if (!$course) {
        return response(null, 404);
    }

    // Eager load students
    $course->load('students');

    // Add studentCount manually
    $data = $course->toArray();
    $data['studentCount'] = $course->students->count();

    return response()->json($data);
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
        $validated = $request->validated();
    // update the student in the database
    $course->update($validated);
    return response(null, 204);
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
