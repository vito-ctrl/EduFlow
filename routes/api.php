<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;

// authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/reset-password', [AuthController::class, 'resetPassword']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);


Route::middleware(['auth:api', 'teacher'])->group(function() {
    Route::post('/teacher', [CourseController::class, 'store']);
    Route::put('/teacher/{id}', [CourseController::class, 'update']);
    Route::delete('/teacher', [CourseController::class, 'destroy']);
});

Route::middleware(['auth:api', 'student'])->group(function() {
    Route::get('/student', [CourseController::class, 'index']);
    Route::get('/student/courses/available', [StudentController::class, 'AvailableCourses']);
    Route::get('/student/courses/match', [StudentController::class, 'MatchCourses']);
});