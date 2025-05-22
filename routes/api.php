<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserProgressTracker;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Health check route
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});

// Course Routes
Route::get('/courses', [CourseController::class, 'getAllCourses']);
Route::post('/courses', [CourseController::class, 'store']);
Route::put('/courses/{course}', [CourseController::class, 'update']);
Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
Route::post('/courses/{course_id}/topics', [TopicController::class, 'store']);
Route::get('/courses/{id}/topics', [CourseController::class, 'getTopicsByCourse']);

// Topic Routes
Route::get('/topics', [TopicController::class, 'getAllTopics']);
Route::post('/topics', [TopicController::class, 'store']);
Route::put('/topics/{id}', [TopicController::class, 'update']);
Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);

// User Routes
Route::get('/users', [AdminController::class, 'getAllUsers']);

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/details', [AuthController::class, 'registerDetails']);

// Progress Route (requires auth)
Route::middleware(['auth:sanctum'])->get('/user-progress', [UserProgressTracker::class, 'userProgress']);
