<?php

use Illuminate\Http\Request;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

// Example API route
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});

Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class, 'store']);
Route::put('/courses/{course}', [CourseController::class, 'update']);
Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
