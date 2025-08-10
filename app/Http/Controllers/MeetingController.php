<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Meeting;
use Illuminate\Http\Request;
use App\Http\Requests\MeetingRequest;
use App\Http\Resources\MeetingResource;

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
        // dd("kontol");
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

    public function recapMeetingsByMonth($courseId, $month)
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

  public function recapMeetings($courseId)
{
    // dd($courseId);
    // Get the course
    $course = Course::findOrFail($courseId);

    // Paginate meetings
    $meetings = Meeting::where('course_id', $courseId)->orderBy('created_at', 'desc')->paginate(100); // Change 20 to your desired items per page

    // Transform the data
    $meetings->getCollection()->transform(function ($meeting) {
        return [
            'id' => $meeting->id,
            'day' => $meeting->day,
            'date' => $meeting->date,
            'time' => $meeting->time,
            'teacher' => $meeting->teacher->name,
        ];
    });

    // Attach course alias to the response
    $response = $meetings->toArray(); // Convert the paginated collection to an array
    $response['course_alias'] = $course->alias;

    return response()->json($response);
}



    public function courseMeetingsbyMonth(Request $request)
    {
        // dd($request->all());
        $courseId = $request->course_id;
        $month = $request->month;
        // dd($courseId, $month);

        $meetings = Meeting::where('course_id', $courseId)
                            ->whereYear('date', substr($month, 0, 4))   // Extract year
                            ->whereMonth('date', substr($month, 5, 2))  // Extract month
                            ->get();

                            // dd($meetings);
        return MeetingResource::collection($meetings);
    }


// get absence based on meeting id
    public function getAbsencesByMeetingId($meetingId)
    {
        // return only the student name in the meeting
        $absences = Meeting::find($meetingId)->absences()->with('studentCourse.student')->get();
       
        return response()->json($absences);
            
    }

   public function dailyRecap(Request $request)
{
    // Ambil parameter tanggal atau gunakan default
    $startDate = $request->query('start_date', now()->format('Y-m-d'));
    $endDate = $request->query('end_date', $startDate); // jika tidak ada end_date, pakai start_date

    $teacherId = $request->query('teacher_id');

    // Query dasar
   // Query dasar + urut terbaru dulu (tanggal & jam)
$query = Meeting::with(['course', 'teacher'])
    ->whereBetween('date', [$startDate, $endDate])
    ->latest(); // = orderBy('created_at','desc')



    // Filter guru jika ada
    if ($teacherId) {
        $query->where('teacher_id', $teacherId);
    }

    // Eksekusi query
    $meetings = $query->get();

    // Transformasi data
    $meetingsData = $meetings->map(function ($meeting) {
        return [
            'id' => $meeting->id,
            'course_alias' => $meeting->course->alias,
            'course_teacher_fee' => $meeting->course->teaching_rate,
            'teacher_name' => $meeting->teacher->name,
            'day' => $meeting->day,
            'date' => $meeting->date,
            'time' => $meeting->time,
        ];
    });

    return response()->json($meetingsData);
}



    

}
