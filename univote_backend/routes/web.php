<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\VoterController;
use App\Http\Middleware\AuthGuard;
use App\Http\Middleware\StaffCheck;

Route::get('/', function () {
    return redirect('/dashboard');
});

// // Admin Login
Route::get('/login', function () {
    return view('adminlogin');
})->name('login');

Route::post('/initlogin', [AdminController::class, 'handleLogin'])->name('admin.handleLogin');


Route::middleware([AuthGuard::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/candidates', [AdminController::class, 'candidates'])->name('admin.candidates');
    Route::get('/voters', [AdminController::class, 'voters'])->name('admin.voters');
    Route::get('/accept-vote', [AdminController::class, 'acceptVote'])->name('admin.acceptvote');
    // Route::get('/accept-candidate', [AdminController::class, 'acceptVote'])->name('admin.acceptcandidate');
    Route::get('/polling', [AdminController::class, 'polling'])->name('admin.polling');
    Route::get('/results', [AdminController::class, 'results'])->name('admin.results');

    Route::delete('/delete-candidate/{id}', [AdminController::class, 'deleteCand'])->name('candidates.delete');
    Route::post('/addcandidate', [AdminController::class, 'addCand']);
    Route::post('/candidate-eligible_checked', [CandidateController::class, 'eligiblityChecked']);
    Route::post('/bylawupload', [AdminController::class, 'byLawPdf']);

    Route::post('/votestat', [AdminController::class, 'updateVoteStat']);

    Route::middleware([StaffCheck::class])->group(function () {
        Route::get('/createstaff', [AdminController::class, 'createStaff'])->name('admin.createstaff');
        Route::post('/toggle-election', [AdminController::class, 'startElection']);
        Route::post('/addstaff', [AdminController::class, 'addUser'])->name('admin.addstaff');
        Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
        Route::post('/voters/toggle-status', [AdminController::class, 'toggleStatusVoter'])->name('voters.toggleStatus');
    });

});