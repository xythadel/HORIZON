<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserProgressTracker;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;

// ✅ Health Check
Route::get('/ping', fn () => response()->json(['message' => 'API is working']));

// ✅ Admin API Routes
Route::prefix('admin')->group(function () {
    // Users
    Route::get('/users', [UserController::class, 'index']);

    // Courses
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{course}', [CourseController::class, 'update']);
    Route::delete('/courses/{course}', [CourseController::class, 'destroy']);

    // Topics (using the correct controller)
    Route::get('/topics', [TopicController::class, 'index']);
    Route::post('/courses/{course}/topics', [TopicController::class, 'store']);
    Route::put('/topics/{topic}', [TopicController::class, 'update']);
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);
});

// ✅ Public Routes
Route::get('/courses', [CourseController::class, 'getAllCourses']);
Route::get('/topics', [TopicController::class, 'getAllTopics'] ?? [TopicController::class, 'index']); // optional
Route::get('/courses/{id}/topics', [CourseController::class, 'getTopicsByCourse']);

// ✅ Progress Tracking (Requires Auth)
Route::middleware(['auth:sanctum'])->get('/user-progress', [UserProgressTracker::class, 'userProgress']);
