<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CompilerController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\LeaderboardsController;
use App\Http\Controllers\LearningProgressController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizAttemptController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\LaravelTopicController; 
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
Route::post('/options/storeOptions', [OptionController::class, 'store']);
Route::delete('/options/{option}', [OptionController::class, 'destroy']);
Route::get('/displayPostQuiz/{id}', [QuestionController::class, 'displayPostQuiz']);
Route::get('/displayPreQuiz/{id}', [QuestionController::class, 'displayPreQuiz']);
Route::get('/options/by-question/{questionId}', [OptionController::class, 'getOptionsByQuestionId']);

Route::get('/user-attempted-topics/{userId}', [LearningProgressController::class, 'getTopicsFromAttempts']);
Route::get('/user-progress/{userId}', [LearningProgressController::class, 'getUserProgress']);

Route::get('/user-attempts/{userId}', [QuizAttemptController::class, 'getAttemptsByUser']);
Route::post('/recordAttempt', [QuizAttemptController::class, 'store']);

Route::get('/post-attempts/{id}', [LeaderboardsController::class, 'postAttempts']);
Route::get('/leaderboard', [LeaderboardsController::class, 'index']);
Route::get('/rating/{id}', [LeaderboardsController::class, 'displayRatings']);

Route::post('/upload-image', [ImageUploadController::class, 'store'])->name('upload.image');

Route::get('/badges/check/{id}', [BadgeController::class, 'check']);
Route::get('/badges/claimed/{id}', [BadgeController::class, 'claimed']);
Route::post('/badges/claim/{id}', [BadgeController::class, 'claim']);
Route::post('/badges', [BadgeController::class, 'store']);
Route::get('/badges', [BadgeController::class, 'index']);

Route::get('/validateBadge/{id}', [BadgeController::class, 'getBadges']);

//Working
Route::get('/reports/course-completion', [BadgeController::class, 'generateCourseCompletionReport']);

//Working
Route::get('/reports/framework-scorecard', [BadgeController::class, 'generateFrameworkProficiencyScorecard']);

//Working
Route::get('/reports/gamification', [BadgeController::class, 'generateGamificationReport']);

//Working
Route::get('/reports/assessment', [BadgeController::class, 'generateAssessmentReport']);

// Working
Route::get('/reports/framework-comparison', [BadgeController::class, 'generateFrameworkComparisonReport']);

// PDF Export Endpoint
Route::get('/reports/export-pdf', [BadgeController::class, 'exportReportToPdf']);

Route::apiResource('lessons', LessonController::class);
Route::get('/topics/{id}/lessons', [LessonController::class, 'byTopic']);

Route::post('/compile', [CompilerController::class, 'runCode']);
Route::post('/simulate-laravel', function (Illuminate\Http\Request $request) {
    $code = $request->input('code');

    if (str_contains($code, 'return view')) {
        return response()->json([
            'output' => 'Simulated: Blade view returned'
        ]);
    }

    return response()->json([
        'output' => 'Unknown Laravel code'
    ]);
});