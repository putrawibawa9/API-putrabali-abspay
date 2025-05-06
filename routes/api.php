<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\RecapitulationController;

Route::prefix('v1')->group(function () {

    // Authentication
    Route::post('/teacher/login',[AuthenticationController::class,'loginTeacher'])->name('teacher.register');
    Route::post('/admin/login',[AuthenticationController::class,'login'])->name('user.login');

    // Entity API routes
        Route::resource('/students', StudentController::class);
        Route::resource('/courses', CourseController::class);
        Route::resource('/teachers', TeacherController::class);
        Route::get('/student/with-active-course', [StudentCourseController::class, 'getStudentsWithActiveCourse']);
    // Operation API routes
        Route::get('/students-courses/{id}', [StudentCourseController::class, 'show']);
        Route::get('/students-courses', [StudentCourseController::class, 'allEnrolledStudents']);
        Route::post('/enrollments', [StudentCourseController::class, 'store']);
        Route::post('/enrollments/update', [StudentCourseController::class, 'update']);
        Route::get('/enrollments', [StudentCourseController::class, 'index']);
        Route::delete('/dropouts/{id}', [StudentCourseController::class, 'destroy']);
        Route::resource('/payments', PaymentController::class);
        Route::resource('/meetings', MeetingController::class);
        Route::resource('/absences', AbsenceController::class);
        Route::get('/courses-search', [CourseController::class, 'search']);
        Route::get('/students-search', [StudentController::class, 'search']);
        Route::get('/teachers-search', [TeacherController::class, 'search'])->name('teachers.search');
        Route::get('/student/payment/{id}', [PaymentController::class, 'getStudentPayment']);
        Route::get('/students/schedules/{nim}', [ScheduleController::class, 'getStudentSchedules']);
        Route::get('/course/meeting-history/{id}', [MeetingController::class, 'getMeetingHistory']);
        Route::get('/absences/meeting/{id}', [MeetingController::class, 'getAbsencesByMeetingId']);
        Route::get('/courses-search', [CourseController::class, 'getCourseBySubject']);



        // Recapitulations
        Route::get('/recapitulations', [RecapitulationController::class, 'index']);
        Route::get('/meetings/recap/{courseId}', [MeetingController::class, 'recapMeetings']);
        Route::get('/meetings/recap/{courseId}/{month}', [MeetingController::class, 'recapMeetingsByMonth']);
        Route::get('/payments/recap/{studentId}/{year}', [PaymentController::class, 'recapStudentPayments']);
        Route::post('/payments/recap', [PaymentController::class, 'paymentRecap']);
        Route::get('/monthly/enrolled/student', [StudentController::class, 'monthlyEnrolledStudent']);
        Route::get('/monthly/payment/student', [PaymentController::class, 'monthlyPaymentStudent']);
        Route::get('/student/absences/history/{id}',  [AbsenceController::class, 'getAbsenceHistory']);
        Route::post('/students/monthly-paid-unpaid',  [PaymentController::class, 'paidAndUnpaidStudentsMonthly']);
        Route::post('/course/monthly/meetings',  [MeetingController::class, 'courseMeetingsbyMonth']);

        Route::post('/payment/create-token', [PaymentController::class , 'createSnapToken'] );
});
