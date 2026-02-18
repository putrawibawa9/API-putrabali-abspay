<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Course;
use App\Models\Absence;
use App\Models\Meeting;
use App\Models\Student;
use App\Models\FinanceEntry;
use Illuminate\Http\Request;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AbsenceRequest;
use App\Http\Resources\MeetingResource;
use Illuminate\Support\Facades\Validator;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meeting = Meeting::with('course', 'teacher')->get();
        return MeetingResource::collection($meeting);
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
public function store(AbsenceRequest $request)
{
    // dd($request->all());
    // create a new meeting record
    $meeting = Meeting::create([
        'course_id' => $request->course_id,
        'day' => $request->day,
        'date' => $request->date,
        'time' => $request->time,
        'teacher_id' => $request->teacher_id,
        'lesson_plan' => $request->lesson_plan,
    ]);
    $absences = [];
    foreach ($request->attendances as $attendance) {
        $absences[] = Absence::create([
            'students_courses_id' => $attendance['students_courses_id'],
            'meeting_id' => $meeting->id,
            'status' => $attendance['status'],
        ]);
    }


    $category = FinanceCategory::where('code', 'K001')->first();
    // dd($category);
    $courseTeachingRate = Course::find($request['course_id'])->teaching_rate ?? 0;

    FinanceEntry::create([
        'finance_category_id' => $category->id,
        'direction' => 'expense',
        'amount' => $courseTeachingRate,
        'note' => 'Honor mengajar untuk pertemuan pada ' . $request['date'] . ' (' . ($request['day'] ?? '-') . '), ' . ($request['time'] ?? '-') . ' - Kursus ID: ' . $request['course_id'],
  
        // 'sourceable_id' akan diisi setelah meeting dibuat
    ]);

   return response(null, 201);
}

    /**
     * Display the specified resource.
     */
    public function show($meeting)
    {
     

    }

public function monthlyAttendance(Request $request, $courseId)
{
    $month = (int) $request->query('month');
    $year  = (int) $request->query('year');

    if (!$month || !$year) {
        return response()->json([
            'message' => 'month dan year wajib diisi'
        ], 422);
    }

    $start = Carbon::create($year, $month, 1)->startOfMonth();
    $end   = Carbon::create($year, $month, 1)->endOfMonth();

    /** ------------------------------------------------
     * TOTAL PERTEMUAN DALAM BULAN
     * ------------------------------------------------ */
    $totalMeetings = DB::table('meetings')
        ->where('course_id', $courseId)
        ->whereBetween('date', [$start, $end])
        ->count();

    /** ------------------------------------------------
     * REKAP ABSENSI SISWA AKTIF SAJA
     * ------------------------------------------------ */
    $students = DB::table('students_courses as sc')
        ->join('students as s', 's.id', '=', 'sc.student_id')

        // 🔥 FILTER SISWA AKTIF
        ->where('sc.course_id', $courseId)
        ->where('sc.is_active', 1)

        ->leftJoin('meetings as m', function ($join) use ($courseId, $start, $end) {
            $join->on('m.course_id', '=', 'sc.course_id')
                 ->whereBetween('m.date', [$start, $end]);
        })
        ->leftJoin('absences as a', function ($join) {
            $join->on('a.meeting_id', '=', 'm.id')
                 ->on('a.students_courses_id', '=', 'sc.id');
        })
        ->select(
            's.id as student_id',
            's.name',
            DB::raw("COUNT(m.id) as total_meetings"),
            DB::raw("SUM(a.status = 'present') as present"),
            DB::raw("SUM(a.status = 'absent') as absent")
        )
        ->groupBy('s.id', 's.name')
        ->get()
        ->map(function ($s) {
            $rate = $s->total_meetings > 0
                ? ($s->present / $s->total_meetings) * 100
                : 0;

            return [
                'student_id'      => $s->student_id,
                'name'            => $s->name,
                'present'         => (int) $s->present,
                'absent'          => (int) $s->absent,
                'attendance_rate' => round($rate, 2),
                // status siswa sudah ditentukan oleh is_active
                'status'          => 'aktif',
            ];
        });

    return response()->json([
        'course_id'      => $courseId,
        'month'          => $month,
        'year'           => $year,
        'total_meetings' => $totalMeetings,
        'students'       => $students
    ]);
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


public function getAbsenceHistory($id)
{
    $student = Student::with([
        'studentsCourses.course',
        'studentsCourses.absences' => function ($query) {
            $query->with('meeting'); // pastikan meeting ikut di-load
        },
    ])->findOrFail($id);

    $absenceHistory = $student->studentsCourses->map(function ($sc) {
        // Urutkan absences dari meeting paling baru (tanggal + waktu jika ada)
        $sortedAbsences = $sc->absences
            ->sortByDesc(function ($a) {
                $date = optional($a->meeting)->date;
                $time = optional($a->meeting)->time;
                return $date . ' ' . ($time ?? '00:00:00');
            })
            ->values();

        return [
            'course' => [
                'alias'   => $sc->course->alias,
                'subject' => $sc->course->subject,
            ],
            'absences' => $sortedAbsences->map(function ($a) {
                $date = optional($a->meeting)->date;
                $time = optional($a->meeting)->time;

                // Format jadi "09 Oktober 2025"
                $formattedDate = $date
                    ? Carbon::parse($date)->locale('id')->translatedFormat('d F Y')
                    : null;

                return [
                    'meeting_date' => $formattedDate, // contoh: "09 Oktober 2025"
                    'meeting_time' => $time,          // biarkan apa adanya; hapus jika tidak perlu
                    'status'       => $a->status,
                ];
            })->toArray(),
        ];
    });

    return response()->json($absenceHistory);
}
}
