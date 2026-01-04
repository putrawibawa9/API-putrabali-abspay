<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Schedule\ScheduleController as NewScheduleController;
use App\Http\Controllers\MeetingController;

// API UNTUK PENJADWALAN
Route::prefix('v1/scheduling')->group(function () {

    Route::post('/generateSemester', [NewScheduleController::class, 'generateSemester']);
  Route::post('/meeting/{meeting}/update', [NewScheduleController::class, 'updateMeeting']);
  Route::post('/course/{course}/change-recurring-schedule', [NewScheduleController::class, 'changeRecurringSchedule']);

  Route::get('/meeting/{id}', [NewScheduleController::class, 'show']);

  Route::get('/schedule', [NewScheduleController::class, 'teacherSchedule']);
  Route::get('/student', [NewScheduleController::class, 'getStudentSchedule']);
Route::get('/all-schedules', [NewScheduleController::class, 'getAllSchedules']);
    
    // Route::get('/teacher/schedule', [MeetingController::class, 'teacherSchedule']);
    // Route::get('/course/schedule/future', [MeetingController::class, 'courseFutureSchedule']);

    // Route::post('{meeting}/change-teacher', [MeetingController::class, 'changeTeacher']);
    // Route::post('{meeting}/change-schedule', [MeetingController::class, 'changeSchedule']);

    // Route::get('/dailyMeeting', [MeetingController::class, 'dailyMeeting']);
}
);
