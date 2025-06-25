<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
class LearningProgressController extends Controller
{
    
    
    public function getTopicsFromAttempts($userId)
    {
        $attempts = QuizAttempt::with(['topic.course'])
            ->where('user_id', $userId)
            ->where('type', 'post')
            ->latest('created_at') // optional: get latest attempts
            ->get()
            ->unique('topic_id'); // ensures only one attempt per topic

        $topics = $attempts->map(function ($attempt) {
            $topic = $attempt->topic;
            $course = $topic?->course;

            return [
                'topic_id' => $topic->id ?? null,
                'title' => $topic->title ?? 'Unknown',
                'score' => $attempt->score,
                'passed' => $attempt->passed,
                'course_id' => $course->id ?? null,
                'course_name' => $course->name ?? 'Unknown',
            ];
        })->filter(); // remove null topics

        return response()->json($topics);
    }

    public function getUserProgress($userId)
    {
        $courses = \App\Models\Course::with('topics')->get();

        $progress_by_course = $courses->map(function ($course) use ($userId) {
            $totalTopics = $course->topics->count();

            // Get passed attempts for this course's topics
            $passedTopicIds = QuizAttempt::where('user_id', $userId)
                ->where('type', 'post')
                ->where('passed', true)
                ->whereIn('topic_id', $course->topics->pluck('id'))
                ->pluck('topic_id')
                ->unique();

            $passedCount = $passedTopicIds->count();

            $progress = $totalTopics > 0 ? round(($passedCount / $totalTopics) * 100) : 0;

            return [
                'course_id' => $course->id,
                'course_name' => $course->name,
                'overall_progress' => $progress,
            ];
        });

        return response()->json([
            'progress_by_course' => $progress_by_course
        ]);
        
    }    


}
