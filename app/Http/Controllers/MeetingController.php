<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'day' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'teacher_id' => 'required|integer|exists:teachers,id',
        ]);

        // Create the new meeting record
        $meeting = Meeting::create([
            'course_id' => $validatedData['course_id'],
            'day' => $validatedData['day'],
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
            'teacher_id' => $validatedData['teacher_id'],
        ]);

        // Return a response indicating success
        return response()->json([
            'success' => true,
            'message' => 'Meeting created successfully',
            'data' => $meeting
        ], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
