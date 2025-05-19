<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\TopicUserInteraction;
use App\Models\QuizUserInteraction;

class UserProgressTracker extends Controller
{
    public function userProgress(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $courses = Course::with('topics.quizzes')->get();

        $progressByCourse = $courses->map(function ($course) use ($user) {
            $topicIds = $course->topics->pluck('id');
            $quizIds = $course->topics->flatMap->quizzes->pluck('id');

            $totalTopics = $topicIds->count();
            $totalQuizzes = $quizIds->count();

            $completedTopics = TopicUserInteraction::where('user_id', $user->id)
                ->whereIn('topic_id', $topicIds)
                ->where('completed', true)
                ->count();

            $completedQuizzes = QuizUserInteraction::where('user_id', $user->id)
                ->whereIn('quiz_id', $quizIds)
                ->where('completed', true)
                ->count();

            $topicProgress = $totalTopics > 0 ? round(($completedTopics / $totalTopics) * 100) : 0;
            $quizProgress = $totalQuizzes > 0 ? round(($completedQuizzes / $totalQuizzes) * 100) : 0;
            $overallProgress = round(($topicProgress + $quizProgress) / 2);

            return [
                'course_id' => $course->id,
                'course_name' => $course->name,
                'topic_progress' => $topicProgress,
                'quiz_progress' => $quizProgress,
                'overall_progress' => $overallProgress,
            ];
        });

        return response()->json(['progress_by_course' => $progressByCourse]);
    }
}
