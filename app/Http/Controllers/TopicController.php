<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // return Topic::all();
        return response()->json(Topic::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $course_id)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',

    ]);

    $topic = new Topic();
    $topic->title = $validated['title'];
    $topic->content = $validated['content'];
    $topic->course_id = $course_id;;
    $topic->save();

    return response()->json($topic, 201);
}


    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        return view('admin.topics.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
       $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $topic->update([
        'title' => $request->title,
        'content' => $request->content,
    ]);

    return response()->json(['message' => 'Topic updated successfully.', 'topic' => $topic]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
       Log::info('Deleting topic:', ['id' => $topic->id]);
       $topic->delete();

      return response()->json(['message' => 'Topic deleted successfully.']);
    }

    public function vueTopics()
    {   
    $topics = Topic::where('course_id', 1)->get();
    return view('admin.topics.vue', compact('topics'));
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
