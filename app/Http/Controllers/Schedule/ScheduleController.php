<?php

namespace App\Http\Controllers\Schedule;

use Carbon\Carbon;
use App\Models\Course;
use App\Models\Meeting;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\UpdateMeetingRequest;
use App\Http\Requests\Schedule\GenerateSemesterRequest;
use App\Http\Requests\Schedule\DeleteRecurringScheduleRequest;
use App\Http\Requests\Schedule\ChangeRecurringScheduleRequest;
use App\Http\Requests\Schedule\StoreRecurringScheduleRequest;
use App\Http\Requests\Schedule\UpdateRecurringScheduleRequest;

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

    public function indexRecurringSchedules(Request $request)
    {
        $request->validate([
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'is_active' => ['nullable', 'boolean'],
            'frequency' => ['nullable', 'in:weekly,monthly'],
        ]);

        $query = Schedule::with(['course:id,alias', 'teacher:id,name'])
            ->withCount(['meetings as future_meetings_count' => function ($q) {
                $q->whereDate('date', '>=', now()->toDateString());
            }])
            ->orderByDesc('created_at');

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        return response()->json($query->get());
    }

    public function storeRecurringSchedule(StoreRecurringScheduleRequest $request)
    {
        $validated = $request->validated();

        [$schedule, $createdMeetings] = DB::transaction(function () use ($validated) {
            $schedule = Schedule::create($validated);

            $dates = $this->generateOccurrenceDates($schedule);
            $this->assertScheduleConflicts(
                schedule: $schedule,
                dates: $dates,
            );

            $meetings = $this->createMeetingsFromSchedule($schedule, $dates);

            return [$schedule->load(['course:id,alias', 'teacher:id,name']), $meetings];
        });

        return response()->json([
            'message' => 'Recurring schedule created successfully.',
            'schedule' => $schedule,
            'created_meetings' => count($createdMeetings),
        ], 201);
    }

    public function showRecurringSchedule(Schedule $schedule)
    {
        $schedule->load([
            'course:id,alias',
            'teacher:id,name',
            'meetings' => fn ($q) => $q->orderBy('date')->orderBy('time'),
        ]);

        return response()->json($schedule);
    }

    public function updateRecurringSchedule(UpdateRecurringScheduleRequest $request, Schedule $schedule)
    {
        $validated = $request->validated();
        $effectiveFrom = isset($validated['effective_from'])
            ? Carbon::parse($validated['effective_from'])->toDateString()
            : max(now()->toDateString(), $schedule->start_date->toDateString());
        $regenerateFutureMeetings = $validated['regenerate_future_meetings'] ?? true;

        $updatedSchedule = DB::transaction(function () use ($schedule, $validated, $effectiveFrom, $regenerateFutureMeetings) {
            $schedule->fill(collect($validated)->except(['effective_from', 'regenerate_future_meetings'])->all());
            $schedule->save();

            if ($regenerateFutureMeetings) {
                $dates = $this->generateOccurrenceDates($schedule, $effectiveFrom);
                $this->assertScheduleConflicts(
                    schedule: $schedule,
                    dates: $dates,
                    ignoreScheduleId: $schedule->id,
                );

                Meeting::where('schedule_id', $schedule->id)
                    ->whereDate('date', '>=', $effectiveFrom)
                    ->delete();

                $this->createMeetingsFromSchedule($schedule, $dates);
            }

            return $schedule->fresh(['course:id,alias', 'teacher:id,name']);
        });

        return response()->json([
            'message' => 'Recurring schedule updated successfully.',
            'schedule' => $updatedSchedule,
            'effective_from' => $effectiveFrom,
        ]);
    }

    public function destroyRecurringSchedule(DeleteRecurringScheduleRequest $request, Schedule $schedule)
    {
        $validated = $request->validated();
        $effectiveFrom = isset($validated['effective_from'])
            ? Carbon::parse($validated['effective_from'])->toDateString()
            : now()->toDateString();
        $deleteFutureMeetings = $validated['delete_future_meetings'] ?? true;

        $deletedMeetings = DB::transaction(function () use ($schedule, $effectiveFrom, $deleteFutureMeetings) {
            $deletedMeetings = 0;

            if ($deleteFutureMeetings) {
                $deletedMeetings = Meeting::where('schedule_id', $schedule->id)
                    ->whereDate('date', '>=', $effectiveFrom)
                    ->delete();
            }

            $schedule->update([
                'is_active' => false,
                'end_date' => Carbon::parse($effectiveFrom)->subDay()->lt($schedule->start_date)
                    ? $schedule->start_date
                    : Carbon::parse($effectiveFrom)->subDay()->toDateString(),
            ]);

            return $deletedMeetings;
        });

        return response()->json([
            'message' => 'Recurring schedule deactivated successfully.',
            'schedule_id' => $schedule->id,
            'deleted_future_meetings' => $deletedMeetings,
            'effective_from' => $effectiveFrom,
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
            'course_id'  => 'nullable|exists:courses,id',

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
// C.1 FILTER: KELAS / COURSE
// ------------------------------------
if ($request->filled('course_id')) {
    $query->where('course_id', $request->course_id);
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
              'course_id'  => $request->course_id,
            'end_date'   => $request->end_date,
        ],
        'count' => $data->count(),
        'data'  => $data,
    ]);
}

    private function generateOccurrenceDates(Schedule $schedule, ?string $fromDate = null): array
    {
        $start = Carbon::parse($fromDate ?? $schedule->start_date)->startOfDay();
        $end = Carbon::parse($schedule->end_date)->startOfDay();

        if ($start->gt($end) || !$schedule->is_active) {
            return [];
        }

        if ($schedule->frequency === 'weekly') {
            return $this->generateWeeklyDates($schedule, $start, $end);
        }

        return $this->generateMonthlyDates($schedule, $start, $end);
    }

    private function generateWeeklyDates(Schedule $schedule, Carbon $start, Carbon $end): array
    {
        $dates = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            if ($cursor->format('l') === $schedule->day_of_week) {
                $dates[] = $cursor->toDateString();
            }

            $cursor->addDay();
        }

        return $dates;
    }

    private function generateMonthlyDates(Schedule $schedule, Carbon $start, Carbon $end): array
    {
        $dates = [];
        $cursor = $start->copy()->startOfMonth();
        $dayOfMonth = (int) $schedule->day_of_month;

        while ($cursor->lte($end)) {
            if ($dayOfMonth <= $cursor->daysInMonth) {
                $candidate = $cursor->copy()->day($dayOfMonth);

                if ($candidate->betweenIncluded($start, $end)) {
                    $dates[] = $candidate->toDateString();
                }
            }

            $cursor->addMonthNoOverflow()->startOfMonth();
        }

        return $dates;
    }

    private function assertScheduleConflicts(Schedule $schedule, array $dates, ?int $ignoreScheduleId = null): void
    {
        foreach ($dates as $date) {
            $teacherConflict = Meeting::query()
                ->where('teacher_id', $schedule->teacher_id)
                ->whereDate('date', $date)
                ->where('time', $schedule->time)
                ->where('is_canceled', false)
                ->when($ignoreScheduleId, fn ($q) => $q->where('schedule_id', '!=', $ignoreScheduleId))
                ->exists();

            if ($teacherConflict) {
                abort(response()->json([
                    'message' => "Guru sudah memiliki jadwal pada {$date} jam {$schedule->time}.",
                ], 422));
            }

            $courseConflict = Meeting::query()
                ->where('course_id', $schedule->course_id)
                ->whereDate('date', $date)
                ->where('time', $schedule->time)
                ->where('is_canceled', false)
                ->when($ignoreScheduleId, fn ($q) => $q->where('schedule_id', '!=', $ignoreScheduleId))
                ->exists();

            if ($courseConflict) {
                abort(response()->json([
                    'message' => "Kelas sudah memiliki jadwal pada {$date} jam {$schedule->time}.",
                ], 422));
            }

            if ($schedule->location) {
                $locationConflict = Meeting::query()
                    ->where('location', $schedule->location)
                    ->whereDate('date', $date)
                    ->where('time', $schedule->time)
                    ->where('is_canceled', false)
                    ->when($ignoreScheduleId, fn ($q) => $q->where('schedule_id', '!=', $ignoreScheduleId))
                    ->exists();

                if ($locationConflict) {
                    abort(response()->json([
                        'message' => "Lokasi {$schedule->location} sudah dipakai pada {$date} jam {$schedule->time}.",
                    ], 422));
                }
            }
        }
    }

    private function createMeetingsFromSchedule(Schedule $schedule, array $dates): array
    {
        $meetings = [];

        foreach ($dates as $date) {
            $meetings[] = Meeting::create([
                'schedule_id' => $schedule->id,
                'course_id' => $schedule->course_id,
                'teacher_id' => $schedule->teacher_id,
                'day' => Carbon::parse($date)->format('l'),
                'date' => $date,
                'time' => $schedule->time,
                'end_time' => $schedule->end_time,
                'location' => $schedule->location,
            ]);
        }

        return $meetings;
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
