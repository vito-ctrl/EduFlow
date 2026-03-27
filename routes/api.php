<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFavorites;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeacherController;



// authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/reset-password', [AuthController::class, 'resetPassword']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);


Route::middleware(['auth:api', 'teacher'])->group(function() {
    Route::post('/teacher', [CourseController::class, 'store']);
    Route::put('/teacher/{id}', [CourseController::class, 'update']);
    Route::delete('/teacher', [CourseController::class, 'destroy']);
    Route::get('/teacher/students', [TeacherController::class, 'getMyStudents']);
    Route::get('/teacher/stats', [TeacherController::class, 'stats']);
});

Route::middleware(['auth:api', 'student'])->group(function() {
    Route::get('/student', [CourseController::class, 'index']);
    Route::get('/student/courses/available', [StudentController::class, 'AvailableCourses']);
    Route::get('/student/courses/match', [StudentController::class, 'MatchCourses']);

    Route::post('/student/courses/favorites/{courseId}', [StudentFavorites::class, 'store']);
    Route::get('/student/courses/favorites/', [StudentFavorites::class, 'index']);

    Route::post('student/courses/payment', [PaymentController::class, 'processPayment']);
    Route::delete('student/courses/{courseId}/withdraw', [PaymentController::class, 'withdraw']);
});