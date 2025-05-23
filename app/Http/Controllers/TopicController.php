<?php
namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopicController extends Controller
{
    public function index()
    {
        return Topic::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);

        $topic = Topic::create($validated);
        return response()->json($topic, 201);
    }

    public function show(Topic $topic)
    {
        return response()->json($topic);
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $topic->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Topic updated successfully.', 'topic' => $topic]);
    }

    public function destroy(Topic $topic)
    {
        Log::info('Deleting topic:', ['id' => $topic->id]);
        $topic->delete();

        return response()->json(['message' => 'Topic deleted successfully.']);
    }

    public function getTopicsByCourse($courseId)
    {
        $course = Course::with('topics')->findOrFail($courseId);
        return response()->json($course->topics);
    }
}
