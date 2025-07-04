<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use DB;
use Illuminate\Http\Request;
use App\Models\QuizAttempt;

class QuizAttemptController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'topic_id' => 'required|exists:topics,id',
            'quiz_id' => 'required|exists:quizzes,id',
            'score' => 'required|integer',
            'total' => 'required|integer',
            'passed' => 'required|boolean',
            'type' => 'required|in:pre,post',
            'time_taken' => 'required|integer',
            'timestamp' => 'required|date',
        ]);

        QuizAttempt::create([
            'user_id' => $validated['user_id'],
            'topic_id' => $validated['topic_id'],
            'quiz_id' => $validated['quiz_id'],
            'score' => $validated['score'],
            'total' => $validated['total'],
            'passed' => $validated['passed'],
            'type' => $validated['type'],
            'time_taken' => $validated['time_taken'],
            'created_at' => $validated['timestamp'],
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Attempt recorded successfully']);
    }

    public function getAttemptsByUser($userId)
    {
        return QuizAttempt::where('user_id', $userId)
            ->where('type', 'post')
            ->get(['topic_id', 'passed']);
    }

    public function getCompletedTopics($userId, $courseId)
    {
        // Get all topics under the course
        $topics = Topic::where('course_id', $courseId)->get();

        $result = [];

        foreach ($topics as $topic) {

            $latestAttempts = DB::table('quiz_attempts as qa')
                ->join(DB::raw('(SELECT quiz_id, MAX(created_at) as latest FROM quiz_attempts WHERE user_id = '.$userId.' AND topic_id = '.$topic->id.' GROUP BY quiz_id) as latest_attempts'), function ($join) {
                    $join->on('qa.quiz_id', '=', 'latest_attempts.quiz_id')
                        ->on('qa.created_at', '=', 'latest_attempts.latest');
                })
                ->where('qa.user_id', $userId)
                ->where('qa.topic_id', $topic->id)
                ->select('qa.quiz_id', 'qa.passed')
                ->get();

            $total = $latestAttempts->count();
            $passed = $latestAttempts->where('passed', true)->count();

            if ($total > 0 && $passed / $total >= 0.6) {
                $result[] = [
                    'topic_id' => $topic->id,
                    'passed' => true,
                ];
            }
        }

        return response()->json($result);
    }

}
