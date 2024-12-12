<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// Admin Login
Route::get('/adminlogin', function () {
    return view('adminlogin');
})->name('adminlogin');

Route::post('/adminlogin', [AdminController::class, 'handleLogin'])->name('admin.handleLogin');

// Dashboard
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Profile
Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');

// Logout
Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Candidates
Route::get('/candidates', [AdminController::class, 'candidates'])->name('admin.candidates');

//Voters
Route::get('/voters', [AdminController::class, 'voters'])->name('admin.voters');

//Polling
Route::get('/polling', [AdminController::class, 'polling'])->name('admin.polling');

//Result
Route::get('/results', [AdminController::class, 'results'])->name('admin.results');