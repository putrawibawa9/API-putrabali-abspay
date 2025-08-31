<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Course;
use App\Models\Meeting;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\StudentCourse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class RecapitulationController extends Controller
{
   public function index(Request $request)
{
    // dd($request->query('year'));
    // Retrieve month and year from the query parameters
    $month = $request->query('month');
    $year = $request->query('year');

    $expectedIncome = DB::table('students_courses as sc')
    ->join('courses as c', 'c.id', '=', 'sc.course_id')
    ->where('sc.is_active', 1)
    ->where('c.is_active', 1)
    ->sum(DB::raw('COALESCE(sc.custom_payment_rate, c.payment_rate)'));

$formatted = 'Rp ' . number_format($expectedIncome, 0, ',', '.');
    // if there is no request month and year, use the current month and year
    if (!$month || !$year) {
        $month = date('m');
        $year = date('Y');
    }
   

    // Convert month name to month number if it's not numeric
    if (!is_numeric($month)) {
        $month = date('m', strtotime($month));
    }

   

    // Query based on the month and year
   $totalStudents = DB::table('students_courses')
    ->where('is_active', 1)
    ->distinct('student_id')
    ->count('student_id');

    $totalEnrollStudentsInGivenMonth = Student::whereMonth('enroll_date', $month)
        ->whereYear('enroll_date', $year)
        ->count();  
    $totalTeachers = Teacher::count();
    $totalActiveCourses = Course::where('is_active', 1)->count();
    $totalMeetingsInGivenMonth = Meeting::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->count();
    
    // Percentage of Students Who Have Paid This Month
    $totalStudentsWhoPaid = Student::whereHas('payments', function ($query) use ($month, $year) {
        $query->whereMonth('created_at', $month)
              ->whereYear('created_at', $year);
    })->count();
    $persentageOfStudentsWhoPaid = ($totalStudentsWhoPaid / $totalStudents) * 100;
    // round to 2 decimal places
    $persentageOfStudentsWhoPaid = round($persentageOfStudentsWhoPaid, 2);
    $totalStudentsWhoHaveNotPaid = $totalStudents - $totalStudentsWhoPaid;
    $percentageOfStudentsWhoHaveNotPaid =  ($totalStudentsWhoHaveNotPaid / $totalStudents) * 100;
    // round to 2 decimal places
    $percentageOfStudentsWhoHaveNotPaid = round($percentageOfStudentsWhoHaveNotPaid, 2);
    // Total Revenue in Given Month from payments table
    $totalRevenue = Payment::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->sum('payment_amount');

    // Percentage of Students Who Are Absent in Given Month
    $totalStudentsWhoAreAbsent = StudentCourse::whereHas('absences', function ($query) use ($month, $year) {
        $query->whereMonth('created_at', $month)
              ->whereYear('created_at', $year)
              ->where('status', 'absent');
    })->count();
    $persentageStudentWhoAreAbsent = ($totalStudentsWhoAreAbsent / $totalStudents) * 100;
    // round to 2 decimal places
    $persentageStudentWhoAreAbsent = round($persentageStudentWhoAreAbsent, 2);


    return response()->json([
        'total_students' => $totalStudents,
        'total_enroll_students_in_given_month' => $totalEnrollStudentsInGivenMonth,
        'total_teachers' => $totalTeachers,
        'total_active_courses' => $totalActiveCourses,
        'total_meetings_in_given_month' => $totalMeetingsInGivenMonth,
        'total_students_who_paid' => $persentageOfStudentsWhoPaid,
        'total_students_who_have_not_paid' => $percentageOfStudentsWhoHaveNotPaid,
        'total_revenue' => $totalRevenue,
        'total_students_who_are_absent' => $persentageStudentWhoAreAbsent,
        'expected_income' => $formatted,
    ]);
}

public function unpaid(Request $request)
    {
        $raw = $request->query('month', now()->format('Y-m'));

        // Normalisasi month
        $monthName = preg_match('/^\d{4}-\d{2}$/', $raw)
            ? strtolower(Carbon::createFromFormat('Y-m', $raw)->format('F'))
            : strtolower($raw);

        $courseId = $request->query('course_id');
        $TYPE = 'spp';

        $rows = DB::table('students_courses as sc')
            ->join('students as s', 's.id', '=', 'sc.student_id')
            ->join('courses as c', 'c.id', '=', 'sc.course_id')
            ->when($courseId, fn($q) => $q->where('sc.course_id', $courseId))
            ->where('sc.is_active', 1)
            ->whereNotExists(function ($q) use ($monthName, $TYPE) {
                $q->select(DB::raw(1))
                  ->from('payments as p')
                  ->whereColumn('p.student_id', 'sc.student_id')
                  ->whereColumn('p.course_id', 'sc.course_id')
                  ->where('p.type', $TYPE)
                  ->whereRaw('LOWER(p.payment_month) = ?', [$monthName]);
            })
            ->select([
                's.id as id',
                's.nis as nis',
                's.name as name',
                's.enroll_date',
                's.wa_number',
                'c.id as course_id',
                'c.alias as course_alias',
                'c.alias as course_alias',
                DB::raw('COALESCE(sc.custom_payment_rate, c.payment_rate) as expected_amount'),
            ])
            ->orderBy('c.alias')->orderBy('s.name')
            ->get();

        // return sebagai API response
        return response()->json([
            'month' => $monthName,
            'course_id' => $courseId,
            'course_alias' => $rows->pluck('course_alias')->first(),
            'count' => $rows->count(),
            'total_money' => $rows->sum('expected_amount'),
            'unpaid_students' => $rows,
        ]);
    }
}
