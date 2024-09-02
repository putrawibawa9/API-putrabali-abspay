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
use App\Http\Controllers\StudentCourseController;

// Entities
Route::resource('/v1/students', StudentController::class);
Route::resource('/v1/courses-available', CourseController::class);
Route::resource('/v1/teachers', TeacherController::class);


Route::get('/v1/students/{student}/courses', [StudentCourseController::class, 'show']);

Route::post('/v1/enrollments', [StudentCourseController::class, 'store']);
Route::get('/v1/enrollments', [StudentCourseController::class, 'index']);
Route::resource('/v1/payments', PaymentController::class);

Route::resource('/v1/meetings', MeetingController::class);
Route::resource('/v1/absence', AbsenceController::class);

