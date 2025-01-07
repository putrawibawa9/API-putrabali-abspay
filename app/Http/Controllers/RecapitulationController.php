<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Meeting;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RecapitulationController extends Controller
{
   public function index(Request $request)
{
    // Retrieve month and year from the query parameters
    $month = $request->query('month');
    $year = $request->query('year');

    // Validate the inputs
    if (!$month || !$year) {
        return response()->json([
            'error' => 'Both month and year are required.',
        ], 400);
    }

    // Convert month name to month number if it's not numeric
    if (!is_numeric($month)) {
        $month = date('m', strtotime($month));
    }

    // Query based on the month and year
    $totalStudents = Student::count();
    $totalEnrollStudentsInGivenMonth = Student::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
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

    return response()->json([
        'total_students' => $totalStudents,
        'total_enroll_students_in_given_month' => $totalEnrollStudentsInGivenMonth,
        'total_teachers' => $totalTeachers,
        'total_active_courses' => $totalActiveCourses,
        'total_meetings_in_given_month' => $totalMeetingsInGivenMonth,
        'total_students_who_paid' => $totalStudentsWhoPaid,
    ]);
}

}
