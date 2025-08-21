<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//  dd(env('APP_TIMEZONE'));
dd(config('app.timezone'));

});


