<?php

use App\Http\Controllers\CompilerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\LaravelTopicController; // ✅ Laravel-specific controller
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserProgressTracker;
use App\Http\Controllers\MyLearningController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use Illuminate\Http\Request;
use App\Models\User;

// ✅ Health check
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});

// ✅ Courses
Route::apiResource('courses', CourseController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

// ✅ Vue Topics (uses shared `topics` table, expects `module_name`)
Route::get('/courses/{id}/topics', [TopicController::class, 'getTopicsByCourse']);
Route::post('/courses/{course_id}/topics', [TopicController::class, 'store']); // expects: title, content, module_name
Route::get('/topics', [TopicController::class, 'index']);
Route::get('/topics/{topic}', [TopicController::class, 'show']);
Route::put('/topics/{topic}', [TopicController::class, 'update']); // expects: title, content, module_name
Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);

// ✅ Laravel Topics (uses separate `laravel_topics` table, expects `module_name`)
Route::get('/courses/{course}/laravel-topics', [LaravelTopicController::class, 'index']);
Route::post('/courses/{course}/laravel-topics', [LaravelTopicController::class, 'store']); // expects: title, content, module_name
Route::put('/laravel-topics/{laravelTopic}', [LaravelTopicController::class, 'update']); // expects: title, content, module_name
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

// Nested Questions under a Quiz
Route::get('/quizzes/{quiz}/questions', [QuestionController::class, 'index']);
Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store']);
Route::get('/questions/{question}', [QuestionController::class, 'show']);
Route::put('/questions/{question}', [QuestionController::class, 'update']);
Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);

// Nested Options under a Question
Route::get('/questions/{question}/options', [OptionController::class, 'index']);
Route::post('/questions/{question}/options', [OptionController::class, 'store']);
Route::get('/options/{option}', [OptionController::class, 'show']);
Route::put('/options/{option}', [OptionController::class, 'update']);
Route::delete('/options/{option}', [OptionController::class, 'destroy']);


Route::post('/compile', [CompilerController::class, 'runCode']);
Route::post('/simulate-laravel', function (Illuminate\Http\Request $request) {
    $code = $request->input('code');

    // 🔒 Only allow safe Laravel-like simulations (not real eval)
    if (str_contains($code, 'return view')) {
        return response()->json([
            'output' => 'Simulated: Blade view returned'
        ]);
    }

    return response()->json([
        'output' => 'Unknown Laravel code'
    ]);
});