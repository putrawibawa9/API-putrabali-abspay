<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Meeting;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RecapitulationController extends Controller
{
   public function index(Request $request)
{
    // dd($request->query('year'));
    // Retrieve month and year from the query parameters
    $month = $request->query('month');
    $year = $request->query('year');

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
    $totalStudents = Student::count();
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

    ]);
}

}
