<?php

namespace App\Http\Controllers\Schedule;

use App\Models\Course;
use App\Models\Meeting;
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

            Meeting::create([
                'course_id'  => $course->id,
                'day'        => $dayName,
                'date'       => $date->format('Y-m-d'),
                'time'       => $daySchedule['time'],
                'teacher_id' => $daySchedule['teacher_id'],
                'location'   => $daySchedule['location'] ?? null,
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


    // Default resource methods (optional)
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
