<?php

namespace App\Http\Controllers\Schedule;

use Carbon\Carbon;
use App\Models\Course;
use App\Models\Meeting;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\ChangeDateRequest;
use App\Http\Requests\Schedule\ChangeTeacherRequest;
use App\Http\Requests\Schedule\UpdateMeetingRequest;
use App\Http\Requests\Schedule\GenerateSemesterRequest;
use App\Http\Requests\Schedule\ChangeRecurringScheduleRequest;

class ScheduleController extends Controller
{
    /**
     * Generate semester meetings.
     */
    public function generateSemester(GenerateSemesterRequest $req)
    {
        $course = Course::findOrFail($req->course_id);

        $period = new \DatePeriod(
            new \DateTime($req->start_date),
            new \DateInterval('P1D'),
            (new \DateTime($req->end_date))->modify('+1 day')
        );

        $count = 0;

        foreach ($period as $date) {

            $dayName = $date->format('l');

            if (!isset($req->schedule[$dayName])) {
                continue;
            }

            $daySchedule = $req->schedule[$dayName];
// dd($daySchedule);
            Meeting::create([
                'course_id'  => $course->id,
                'day'        => $dayName,
                'date'       => $date->format('Y-m-d'),
                'time'       => $daySchedule['time'],
                'teacher_id' => $daySchedule['teacher_id'],
                'location'   => $daySchedule['location'],
            ]);

            $count++;
        }

        return response()->json([
            'message' => 'Semester schedule generated successfully.',
            'created' => $count
        ]);
    }
public function updateMeeting(UpdateMeetingRequest $request, Meeting $meeting)
{
    $oldData = $meeting->only(['teacher_id', 'date', 'time', 'location', 'day']);

    // Ambil nilai baru (kalau tidak dikirim, pakai nilai lama)
    $newTeacher  = $request->teacher_id ?? $meeting->teacher_id;
    $newDate     = $request->date ?? $meeting->date;
    $newTime     = $request->time ?? $meeting->time;
    $newLocation = $request->location ?? $meeting->location;

    // Generate day dari date baru
    $newDay = date('l', strtotime($newDate));

    // Update meeting
    $meeting->update([
        'teacher_id' => $newTeacher,
        'date'       => $newDate,
        'time'       => $newTime,
        'location'   => $newLocation,
        'day'        => $newDay,
    ]);

    return response()->json([
        'message' => 'Meeting berhasil diperbarui.',
        'old'     => $oldData,
        'new'     => $meeting
    ]);
}

public function changeRecurringSchedule(ChangeRecurringScheduleRequest $request, Course $course)
 {
    $oldDay        = $request->old_day;
    $newDay        = $request->new_day;
    $newTime       = $request->new_time;
    $effectiveFrom = $request->effective_from;

    $newTeacher  = $request->teacher_id;
    $newLocation = $request->location;

    // ambil semua meeting yang day = old_day dan date >= effectiveFrom
    $meetings = Meeting::where('course_id', $course->id)
        ->where('day', $oldDay)
        ->where('date', '>=', $effectiveFrom)
        ->orderBy('date')
        ->get();

    $updated = [];

    foreach ($meetings as $meeting) {

        // hitung tanggal baru (next newDay setelah tanggal meeting lama)
        $currentDate = \Carbon\Carbon::parse($meeting->date);
        $newDate     = $currentDate->next($newDay)->format('Y-m-d');

        // Ambil guru baru atau guru lama
        $teacherId = $newTeacher ?? $meeting->teacher_id;

        // cek guru bentrok
        $conflict = Meeting::where('teacher_id', $teacherId)
            ->where('date', $newDate)
            ->where('time', $newTime)
            ->where('id', '!=', $meeting->id)
            ->exists();

        if ($conflict) {
            $teacher = \App\Models\Teacher::find($teacherId);
            $name = $teacher ? $teacher->name : 'Guru';

            return response()->json([
                'message' => "Gagal: Guru $name bentrok pada $newDay jam $newTime.",
                'meeting' => $meeting
            ], 422);
        }

        // update meeting
        $meeting->update([
            'day'        => $newDay,
            'date'       => $newDate,
            'time'       => $newTime,
            'teacher_id' => $teacherId,
            'location'   => $newLocation ?? $meeting->location
        ]);

        $updated[] = $meeting;
    }

    return response()->json([
        'message' => 'Recurring schedule successfully updated.',
        'updated_meetings' => $updated
    ], 200);
}


public function teacherSchedule(Request $req)
{
    $req->validate([
        'teacher_id' => 'required|exists:teachers,id',
        'type'       => 'nullable|in:future,history,all'
    ]);

    $teacherId = $req->teacher_id;
    $type = $req->type ?? 'all';
    $today = now()->toDateString();

    // Query dasar: guru utama atau guru pengganti
    $query = Meeting::with([
                'course:id,alias',
                'teacher:id,name',
           
            ])
            ->where(function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId)
                  ->orWhere('original_teacher_id', $teacherId);
            })
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc');

    // Filter tipe jadwal
    if ($type === 'future') {
        $query->where('date', '>=', $today)
              ->where('is_canceled', false);
    }
    else if ($type === 'history') {
        $query->where('date', '<', $today);
    }

    $meetings = $query->get();

    // Transformasi data (lebih rapih untuk frontend)
    $clean = $meetings->map(function ($m) use ($teacherId) {
        return [
            'id'            => $m->id,
            'date'          => $m->date,
            'day'           => \Carbon\Carbon::parse($m->date)->format('l'),
            'time'          => $m->time,
            'course_alias'  => $m->course->alias ?? null,
            'location'      => $m->location,
            'is_canceled'   => (bool) $m->is_canceled,
            'teacher'       => $m->teacher->name ?? null,
            'original_teacher' => $m->originalTeacher->name ?? null,
            'is_replacement' => $m->original_teacher_id 
                                    ? ($m->original_teacher_id != $m->teacher_id)
                                    : false,
        ];
    });

    return response()->json([
        'teacher_id' => $teacherId,
        'type'       => $type,
        'generated'  => now()->toDateTimeString(),
        'count'      => $clean->count(),
        'data'       => $clean
    ]);
}


public function getStudentSchedule(Request $req)
{
    $req->validate([
        'student_id' => 'required|exists:students,id',
    ]);

    $today = Carbon::today()->toDateString();

    // 1. Ambil student + course yang diikuti
    $student = Student::with('courses:id,alias')
        ->findOrFail($req->student_id);

    $courseIds = $student->courses->pluck('id')->toArray();

    // 2. Ambil meeting dari course tersebut (future only)
    $meetings = Meeting::with([
            'course:id,alias',
            'teacher:id,name'
        ])
        ->whereIn('course_id', $courseIds)
        ->where('date', '>=', $today)
        ->where('is_canceled', false)
        ->orderBy('date', 'asc')
        ->orderBy('time', 'asc')
        ->get();

    // 3. Transform agar frontend-friendly
    $schedule = $meetings->map(function ($m) {
        return [
            'date'          => $m->date,
            'day'           => Carbon::parse($m->date)->format('l'),
            'time'          => $m->time,
            'course_alias'  => $m->course->alias ?? null,
            'teacher'       => $m->teacher->name ?? null,
            'location'      => $m->location,
        ];
    });

    return response()->json([
        'student_id' => $student->id,
        'generated'  => now()->toDateTimeString(),
        'count'      => $schedule->count(),
        'schedule'   => $schedule
    ]);
}


public function getAllSchedules(Request $request)
{

    
    // ------------------------------------
    // A. VALIDASI INPUT
    // ------------------------------------
    $request->validate([
        'teacher_id' => 'nullable|exists:teachers,id',
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
    ]);

    // ------------------------------------
    // B. QUERY DASAR
    // ------------------------------------
    $query = Meeting::with([
        'course:id,alias',
        'teacher:id,name',
       
    ])
    ->orderBy('date', 'asc')
    ->orderBy('time', 'asc');

    // ------------------------------------
// B.1 FILTER: SCHEDULE = BELUM TERJADI
// ------------------------------------
$today = Carbon::today()->toDateString();
$query->where('date', '>=', $today);

    // ------------------------------------
    // C. FILTER: GURU
    // ------------------------------------
    if ($request->filled('teacher_id')) {
        $teacherId = $request->teacher_id;

        $query->where(function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
             
        });
    }

    // ------------------------------------
    // D. FILTER: START DATE
    // ------------------------------------
    if ($request->filled('start_date')) {
        $query->where('date', '>=', $request->start_date);
    }

    // ------------------------------------
    // E. FILTER: END DATE
    // ------------------------------------
    if ($request->filled('end_date')) {
        $query->where('date', '<=', $request->end_date);
    }

    // ------------------------------------
    // F. EKSEKUSI QUERY
    // ------------------------------------
    $meetings = $query->get();

    // ------------------------------------
    // G. TRANSFORM RESPONSE (frontend-friendly)
    // ------------------------------------
    $data = $meetings->map(function ($m) {
        return [
            'id'              => $m->id,
            'date'            => $m->date,
            'day'             => Carbon::parse($m->date)->format('l'),
            'time'            => $m->time,
            'course_alias'    => $m->course->alias ?? null,
            'teacher'         => $m->teacher->name ?? null,
            'original_teacher'=> $m->originalTeacher->name ?? null,
            'is_replacement'  => $m->original_teacher_id 
                                  && $m->original_teacher_id != $m->teacher_id,
            'location'        => $m->location,
            'is_canceled'     => (bool) $m->is_canceled,
        ];
    });

    return response()->json([
        'filters' => [
            'teacher_id' => $request->teacher_id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ],
        'count' => $data->count(),
        'data'  => $data,
    ]);
}



    // Default resource methods (optional)
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {
            // get meeting by id
            $meeting = Meeting::findOrFail($id);
            return response()->json($meeting);
    }
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
