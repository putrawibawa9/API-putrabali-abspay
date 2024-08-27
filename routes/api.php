<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCourseController;

Route::resource('/v1/students', StudentController::class);
Route::resource('/v1/courses', CourseController::class);
Route::get('/v1/students/{student}/courses', [StudentCourseController::class, 'show']);

Route::post('/v1/enrollments', [StudentCourseController::class, 'store']);
Route::resource('/v1/payments', PaymentController::class);