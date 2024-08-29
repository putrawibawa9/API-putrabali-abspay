<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceController extends Controller
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
            'student_id' => 'required|integer|exists:students,id',
            'meeting_id' => 'required|integer|exists:meetings,id',
            'status' => 'required|string|in:present,absent',
        ]);

        // Create the attendance record
        $absence = Absence::create([
            'student_id' => $validatedData['student_id'],
            'meeting_id' => $validatedData['meeting_id'],
            'status' => $validatedData['status'],
        ]);

        // Return a response indicating success
        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully',
            'data' => $absence
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Absence $absence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absence $absence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absence $absence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absence $absence)
    {
        //
    }
}
