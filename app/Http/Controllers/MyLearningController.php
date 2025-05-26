<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCourse;
use App\Models\Topic;
use App\Models\User;
use App\Models\Course;

class MyLearningController extends Controller
{
    /**
     * Start a course for the authenticated user.
     */
    public function start($courseId)
    {
        $user = Auth::user();

        $firstTopic = Topic::where('course_id', $courseId)->orderBy('id')->first();

        $userCourse = UserCourse::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $courseId],
            ['last_topic_id' => $firstTopic?->id, 'completed' => false]
        );

        return response()->json($userCourse);
    }

    /**
     * Get progress for courses the user has started.
     */
   public function progress()
{
    $user = Auth::user();

    $progressData = UserCourse::with(['course.topics', 'lastTopic'])
        ->where('user_id', $user->id)
        ->get()
        ->map(function ($userCourse) {
            $topics = $userCourse->course->topics->sortBy('id')->values();
            $total = $topics->count();

            $progressIndex = 0;

            if ($userCourse->last_topic_id && $total > 0) {
                $index = $topics->search(function ($topic) use ($userCourse) {
                    return $topic->id === $userCourse->last_topic_id;
                });

                // index is zero-based, so add 1 to count that topic as completed
                $progressIndex = $index !== false ? $index + 1 : 0;
            }

            $percentage = $total > 0 ? intval(($progressIndex / $total) * 100) : 0;

            return [
                'name' => $userCourse->course->name,
                'progress' => $percentage,
            ];
        });

    return response()->json($progressData);
}

    /**
     * Update the user's last visited topic in a course.
     */
    public function updateTopic($courseId, $topicId)
    {
        $user = Auth::user();

        UserCourse::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->update(['last_topic_id' => $topicId]);

        return response()->json(['message' => 'Progress updated']);
    }
}
