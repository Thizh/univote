<?php

use App\Http\Controllers\SignUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/student-details', [SignUpController::class, 'getStudentDetails']);
