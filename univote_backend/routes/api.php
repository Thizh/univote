<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\SignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoterController;

Route::get('/test-connection', function () {
    return response()->json(['message' => 'Backend is connected!']);
});

// Route::get('/decrypt-vote', [DecryptionController::class, 'decryptVote']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/student-details', [SignUpController::class, 'getStudent']);
Route::post('/checkuser', [SignUpController::class, 'check']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/first-time', [SignUpController::class, 'isFirstTime']);
});

// Route::post('/first-time', [SignUpController::class, 'isFirstTime']);

Route::post('/setdata', [SignUpController::class, 'saveUserData']);
Route::post('/checkotp', [SignUpController::class, 'checkOTP']);

//candidate 
Route::post('/assign-qr', [CandidateController::class, 'candidateApply']);
Route::get('/getCandidate', [CandidateController::class, 'getData']);

//vote
Route::post('/placevote', [VoterController::class, 'vote']);

//stats
Route::get('/getstats', [VoterController::class, 'getStats']);

Route::post('/adminLogin', [AdminController::class, 'mobileLogin']);
Route::post('/qrsend', [AdminController::class, 'qrScanned']);
Route::post('/acceptv', [AdminController::class, 'acceptv']);

Route::post('/accept-or-reject', [AdminController::class, 'acceptVoter']);
Route::get('/isVoted/{id}', [VoterController::class, 'isVoted']);

Route::get('/isApplied/{id}', [VoterController::class, 'isApplied']);
Route::post('/saveqr', [VoterController::class, 'saveQR']);

Route::post('/bylawupload', [AdminController::class, 'byLawPdf']);
Route::get('/download-svg/{user_id}', [VoterController::class, 'downloadSvg']);

Route::get('/is-started', [SignUpController::class, 'isElectionStarted']);
