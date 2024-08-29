<?php

use App\Http\Controllers\AbsenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\MeetingController;




Route::resource('/v1/students', StudentController::class);
Route::resource('/v1/courses', CourseController::class);
Route::get('/v1/students/{student}/courses', [StudentCourseController::class, 'show']);

Route::post('/v1/enrollments', [StudentCourseController::class, 'store']);
Route::resource('/v1/payments', PaymentController::class);

Route::resource('/v1/meetings', MeetingController::class);
Route::resource('/v1/absence', AbsenceController::class);

