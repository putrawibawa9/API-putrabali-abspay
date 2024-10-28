<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeetingRequest;
use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $meetings = Meeting::with('course', 'teacher')->get();  // Ensure to load course and teacher relationships
        return MeetingResource::collection($meetings);
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
    public function store(MeetingRequest $request)
    {
        // Create the new meeting record
        Meeting::create([
            'course_id' => $request['course_id'],
            'day' => $request['day'],
            'date' => $request['date'],
            'time' => $request['time'],
            'teacher_id' => $request['teacher_id'],
        ]);
        return response(null, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //  Fetch the meeting record by ID
        $meeting = Meeting::with('course', 'teacher')->find($id);
        return new MeetingResource($meeting);
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
        Meeting::destroy($id);
        return response(null, 204);
    }

    public function recapMeetings($courseId, $month)
    {
        // Assuming $month is in the format 'YYYY-MM'
        $meetingsCount = Meeting::where('course_id', $courseId)
                                ->whereYear('date', substr($month, 0, 4))   // Extract year
                                ->whereMonth('date', substr($month, 5, 2))  // Extract month
                                ->count();

        return response()->json([
            'course_id' => $courseId,
            'month' => $month,
            'meetings_count' => $meetingsCount,
        ]);
    }
}
