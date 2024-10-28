<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentCourseController;

Route::prefix('v1')->group(function () {

    // Entity API routes
        Route::resource('/students', StudentController::class);
        Route::resource('/courses', CourseController::class);
        Route::resource('/teachers', TeacherController::class);
    // Operation API routes
        Route::get('/students-courses/{id}', [StudentCourseController::class, 'show']);
        Route::get('/students-courses', [StudentCourseController::class, 'allEnrolledStudents']);
        Route::post('/enrollments', [StudentCourseController::class, 'store']);
        Route::get('/enrollments', [StudentCourseController::class, 'index']);
        Route::delete('/dropouts/{id}', [StudentCourseController::class, 'destroy']);
        Route::resource('/payments', PaymentController::class);
        Route::resource('/meetings', MeetingController::class);
        Route::resource('/absences', AbsenceController::class);
        Route::get('/students/schedules/{nim}', [ScheduleController::class, 'getStudentSchedules']);
        Route::get('/meetings/recap/{courseId}/{month}', [MeetingController::class, 'recapMeetings']);
        Route::get('/payments/recap/{studentId}/{year}', [PaymentController::class, 'recapStudentPayments']);
        Route::post('/courses/search', [CourseController::class, 'search']);
        Route::get('/student/payment/{id}', [PaymentController::class, 'getStudentPayment']);
});
