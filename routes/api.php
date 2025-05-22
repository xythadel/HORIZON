<?php

use Illuminate\Http\Request;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserProgressTracker;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\AuthController;



// Example API route
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});

;
//API routes for courses, topics, quizzes, and user progress
//Courses CRUD
Route::apiResource('courses', CourseController::class);
Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class, 'store']);
Route::put('/courses/{course}', [CourseController::class, 'update']);
Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
Route::get('/courses/{id}/topics', [TopicController::class, 'getTopicsByCourse']);
Route::post('/courses/{course_id}/topics', [TopicController::class, 'store']);
//Topics CRUD
Route::get('/topics', [TopicController::class, 'index']);
Route::post('/topics', [TopicController::class, 'store']);
Route::put('/topics/{id}', [TopicController::class, 'update']);
Route::delete('/topics/{id}', [TopicController::class, 'destroy']);
Route::get('/courses/{id}/topics', [TopicController::class, 'getTopicsByCourse']);
//USERS
Route::get('/users', function () {
    return User::select('id', 'name', 'email', 'role', 'created_at')->get();
});


//sign up route
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->get('/user-progress', [UserProgressTracker::class, 'userProgress']);
