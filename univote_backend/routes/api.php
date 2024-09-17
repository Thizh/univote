<?php

use App\Http\Controllers\SignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/student-details', [SignUpController::class, 'getStudent']);
Route::post('/checkuser', [SignUpController::class, 'check']);