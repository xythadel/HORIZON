<?php
namespace App\Http\Controllers;

use App\Models\LaravelTopic;
use App\Models\Course;
use App\Models\Topic;
use Illuminate\Http\Request;

class LaravelTopicController extends Controller
{
    public function index(Course $course)
    {
        // Return all Laravel topics for a specific course
        return LaravelTopic::where('course_id', $course->id)->get();
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $topic = LaravelTopic::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return response()->json($topic, 201);
    }

    public function update(Request $request, LaravelTopic $laravelTopic)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $laravelTopic->update($validated);

        return response()->json($laravelTopic);
    }

    public function destroy(LaravelTopic $laravelTopic)
    {
        $laravelTopic->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
    
public function laravelTopics()
    {
    $topics = Topic::where('course_id', 2)->get();
    return view('admin.topics.laravel', compact('topics'));
    }
        public function getTopicsByCourse($courseId)
    {
    $course = Course::with('topics')->findOrFail($courseId);
    return response()->json($course->topics);
    }
}
