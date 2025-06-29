<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::with('topic');

        if ($request->has('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        return response()->json($query->get());
    }
    public function byTopic($topicId)
    {
        $lessons = Lesson::where('topic_id', $topicId)->get();
        return response()->json($lessons);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'difficulty' => 'required|in:1,2,3',
            'content' => 'required|string',
        ]);

        $lesson = Lesson::create($validated);

        return response()->json($lesson, 201);
    }

    public function show($id)
    {
        $lesson = Lesson::with('topic')->findOrFail($id);
        return response()->json($lesson);
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'difficulty' => 'in:1,2,3',
            'content' => 'string',
        ]);

        $lesson->update($validated);

        return response()->json($lesson);
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully']);
    }
}