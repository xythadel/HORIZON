<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardsController extends Controller
{
    public function postAttempts($id)
{
    // Get all post attempts
    $allAttempts = QuizAttempt::where('user_id', $id)
        ->where('type', 'post')
        ->with('topic:id,title')
        ->get();

    // Group by topic_id
    $groupedByTopic = $allAttempts->groupBy('topic_id');

    $topicAttempts = [];
    $totalScore = 0;

    foreach ($groupedByTopic as $topicId => $attempts) {
        // Keep only unique quiz_id per topic
        $uniqueAttempts = $attempts->unique('quiz_id');

        // Sum scores for this topic
        $topicScore = $uniqueAttempts->sum('score');

        $totalScore += $topicScore;

        $topicTitle = optional($attempts->first()->topic)->title ?? 'Unknown';

        $topicAttempts[] = [
            'topic_id' => $topicId,
            'topic_title' => $topicTitle,
            'score' => $topicScore,
            'attempts_count' => $uniqueAttempts->count(),
        ];
    }

    return response()->json([
        'total_score' => $totalScore,
        'topics' => $topicAttempts,
    ]);
}




    public function displayRatings($id)
{
    $user = User::findOrFail($id);
    $courses = Course::with('topics')->get();

    // Fetch all post-type quiz attempts by this user
    $rawAttempts = QuizAttempt::where('user_id', $id)
        ->where('type', 'post')
        ->with('topic:id,title,course_id')
        ->orderByDesc('attempted_at')
        ->get();

    // Group by topic and filter duplicate quiz_ids
    $groupedByTopic = $rawAttempts->groupBy('topic_id');

    // Topic => [score, time, title]
    $topicStats = $groupedByTopic->map(function ($attempts) {
        $unique = $attempts->unique('quiz_id');
        $topic = $attempts->first()->topic;

        return [
            'topic_id'   => $topic->id,
            'title'      => $topic->title,
            'score'      => $unique->sum('score'),
            'time_taken' => $unique->sum('time_taken'),
            'course_id'  => $topic->course_id,
        ];
    });

    // Map to course progress structure
    $result = $courses->map(function ($course) use ($topicStats) {
        $topics = $course->topics;

        $completed = $topics->filter(fn($topic) => $topicStats->has($topic->id));

        return [
            'course_name'      => $course->name,
            'topics_total'     => $topics->count(),
            'topics_completed' => $completed->count(),
            'overall_progress' => $topics->count() > 0
                ? round(($completed->count() / $topics->count()) * 100)
                : 0,
            'topics'           => $completed->map(function ($topic) use ($topicStats) {
                $data = $topicStats[$topic->id];
                return [
                    'title'      => $data['title'],
                    'score'      => $data['score'],
                    'time_taken' => $data['time_taken'],
                ];
            })->values()
        ];
    });

    return response()->json([
        'user_id' => $user->id,
        'progress_by_course' => $result
    ]);
}


    public function index()
    {
        $users = User::select('users.id', 'users.firstname')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->where('quiz_attempts.passed', true)
            ->groupBy('users.id', 'users.firstname')
            ->selectRaw('COUNT(DISTINCT quiz_attempts.topic_id) as topics_completed')
            ->selectRaw('AVG(quiz_attempts.score) as average_score')
            ->orderByDesc('topics_completed')
            ->orderByDesc('average_score')
            ->get();

        return response()->json($users);
    }
}