<?php

use App\Http\Controllers\SignUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/student-details', [SignUpController::class, 'getStudent']);
