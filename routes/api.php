<?php

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserProgressTracker;
use App\Http\Controllers\MyLearningController;
use App\Models\Topic;
use App\Models\User;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// --- Original Routes ---

// Health check route
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});


// Course Routes
Route::apiResource('courses', CourseController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy']);
Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class, 'store']);
Route::post('/courses/{course}/topics', [TopicController::class, 'store']);

// Topic Routes
Route::get('/courses/{id}/topics', [TopicController::class, 'getTopicsByCourse']);
Route::get('/topics', [TopicController::class, 'index']);
//this delete route is for deleting a topic by its ID
Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/mylearning/start/{courseId}', [MyLearningController::class, 'start']);
    Route::get('/mylearning/progress', [MyLearningController::class, 'progress']);
    Route::put('/mylearning/update-topic/{courseId}/{topicId}', [MyLearningController::class, 'updateTopic']);
});



