<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Topic;
use App\Models\Quiz;
use App\Models\TopicUserInteraction;
use App\Models\QuizUserInteraction;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Might be null during development if not logged in
        $courses = Course::with('topics')->get(); // optionally eager load related data

        // Default progress
        $progress = 0;

        if ($user) {
            // Count all topics and quizzes in the system
            $totalTopics = Topic::count();
            $totalQuizzes = Quiz::count();

            // Count completed by user
            $completedTopics = TopicUserInteraction::where('user_id', $user->id)
                ->where('completed', true)
                ->count();

            $completedQuizzes = QuizUserInteraction::where('user_id', $user->id)
                ->where('completed', true)
                ->count();

            $totalItems = $totalTopics + $totalQuizzes;
            $completedItems = $completedTopics + $completedQuizzes;

            $progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
        }

        return Inertia::render('Dashboard', [
            'user' => $user,
            'isAdmin' => optional($user)->role === 'admin',
            'courses' => $courses,
            'progress' => $progress,
        ]);
    }
}
