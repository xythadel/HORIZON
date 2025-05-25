<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserProgressTracker;
use App\Models\Topic;
use App\Models\User;

// Health check route
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});

// --- Original Routes ---

// Course Routes
Route::apiResource('courses', CourseController::class);
Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class, 'store']);
Route::get('/courses/{course}', [CourseController::class, 'show']);
// Topic Routes
Route::get('/courses/{id}/topics', [TopicController::class, 'getTopicsByCourse']);
Route::get('/topics', [TopicController::class, 'index']);

// User Progress (protected)
Route::middleware(['auth:sanctum'])->get('/user-progress', [UserProgressTracker::class, 'userProgress']);
Route::get('/users', function () {
    return User::select('id', 'name', 'email', 'role', 'created_at')->get();
});

Route::prefix('courses/{course}')->group(function () {
    Route::get('topics', [TopicController::class, 'index']);
    Route::post('topics', [TopicController::class, 'store']);
    Route::put('topics/{topic}', [TopicController::class, 'update']);
    Route::delete('topics/{topic}', [TopicController::class, 'destroy']);
});


// --- Admin Aliases (for frontend compatibility) ---

Route::prefix('admin')->group(function () {
    // Route aliases for frontend Axios calls
    Route::get('/topics', [TopicController::class, 'index']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
});
