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
