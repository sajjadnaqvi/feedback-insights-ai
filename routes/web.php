<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::prefix('/feedback')->group( function () {
        Route::get('/give', [FeedbackController::class, 'showGiveFeedback'])->name('feedback.give');
        Route::post('/give', [FeedbackController::class, 'storeFeedback'])->name('feedback.store');
        Route::group(['middleware' => 'admin'], function () {
            Route::get('/', [FeedbackController::class, 'index'])->name('feedback.index');    
        });
    });
    
});
