<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\LaravelTopicController; // ✅ New controller
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserProgressTracker;
use App\Http\Controllers\MyLearningController;
use Illuminate\Http\Request;
use App\Models\User;

// ✅ Health check
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});

// ✅ Courses
Route::apiResource('courses', CourseController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class, 'store']);

// ✅ Vue Topics (uses shared `topics` table)
Route::get('/courses/{id}/topics', [TopicController::class, 'getTopicsByCourse']);
Route::post('/courses/{course_id}/topics', [TopicController::class, 'store']);
Route::get('/topics', [TopicController::class, 'index']);
Route::get('/topics/{topic}', [TopicController::class, 'show']);
Route::put('/topics/{topic}', [TopicController::class, 'update']);
Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);

// ✅ Laravel Topics (uses separate `laravel_topics` table)
Route::get('/courses/{course}/laravel-topics', [LaravelTopicController::class, 'index']);
Route::post('/courses/{course}/laravel-topics', [LaravelTopicController::class, 'store']);
Route::put('/laravel-topics/{laravelTopic}', [LaravelTopicController::class, 'update']);
Route::delete('/laravel-topics/{laravelTopic}', [LaravelTopicController::class, 'destroy']);

// ✅ Users (admin listing)
Route::get('/users', function () {
    return User::select('id', 'name', 'email', 'role', 'created_at')->get();
});

// ✅ User progress (protected)
Route::middleware(['auth:sanctum'])->get('/user-progress', [UserProgressTracker::class, 'userProgress']);

// ✅ MyLearning (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/mylearning/start/{courseId}', [MyLearningController::class, 'start']);
    Route::get('/mylearning/progress', [MyLearningController::class, 'progress']);
    Route::put('/mylearning/update-topic/{courseId}/{topicId}', [MyLearningController::class, 'updateTopic']);
});

// ✅ Quiz routes
Route::get('/quizzes', [QuizController::class, 'index']);
Route::post('/quizzes', [QuizController::class, 'store']);
Route::put('/quizzes/{quiz}', [QuizController::class, 'update']);
Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy']);
