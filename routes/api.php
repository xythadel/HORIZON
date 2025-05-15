<?php

use Illuminate\Http\Request;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



// Example API route
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});

;

Route::apiResource('courses', CourseController::class);
Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class, 'store']);
Route::put('/courses/{course}', [CourseController::class, 'update']);
Route::delete('/courses/{course}', [CourseController::class, 'destroy']);

Route::middleware(['middleware' => 'auth:sanctum'])->get('/user-progress', function () {
    $user = Auth::user();

    if (!$user) {
        return ['error' => 'Unauthenticated'];
    }

    // Debugging line:
    dd(get_class($user), method_exists($user, 'calculateTopicProgress'));

    return [
        'topicProgress' => $user->calculateTopicProgress(),
        'quizProgress' => $user->calculateQuizProgress(),
    ];
});