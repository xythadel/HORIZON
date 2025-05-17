<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Models\User;  
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;

class UserProgressTracker extends Controller
{
    // ProgressController.php
public function userProgress()
{
    $user = auth()->user();

    $totalTopics = Topic::count();
    $completedTopics = $user->progress()->whereNotNull('topic_id')->where('completed', true)->count();

    $totalQuizzes = Quiz::count();
    $completedQuizzes = $user->progress()->whereNotNull('quiz_id')->where('completed', true)->count();

    $topicProgress = $totalTopics ? round(($completedTopics / $totalTopics) * 100) : 0;
    $quizProgress = $totalQuizzes ? round(($completedQuizzes / $totalQuizzes) * 100) : 0;

    return response()->json([
        'topicProgress' => $topicProgress,
        'quizProgress' => $quizProgress
    ]);
}

}
