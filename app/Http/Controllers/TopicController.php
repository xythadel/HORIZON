<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Topic::all();
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
        'module_name' => 'required|string|max:255',
        'difficulty' => 'required|in:1,2,3',
        'content' => 'required|string',

    ]);

     $course = Course::findOrFail($course_id); 

    $topic = new Topic();
    $topic->title = $validated['title'];
    $topic->module_name = $validated['module_name'];
    $topic->course_id = $course_id;;
    $topic->difficulty = $validated['difficulty'];
    $topic->content = $validated['content'];
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
        'module_name' => 'required|string|max:255',
        'difficulty' => 'required|in:1,2,3',
        'content' => 'required|string',
    ]);

    $topic->update([
        'title' => $request->title,
        'module_name' => $request->module_name,
        'difficulty' => $request->difficulty,
        'content' => $request->content,
    ]);

    return response()->json(['message' => 'Topic updated successfully.', 'topic' => $topic]);
    }

    public function destroy(Topic $topic)
    {
        Log::info('Deleting topic:', ['id' => $topic->id]);


        DB::table('user_courses')
            ->where('last_topic_id', $topic->id)
            ->update(['last_topic_id' => null]);

        $topic->delete();

        return response()->json(['message' => 'Topic deleted successfully.']);
    }

    public function vueTopics()
    {   
        $topics = Topic::where('course_id', 1)->get();
        return view('admin.topics.vue', compact('topics'));
    }
    public function getTopicsByCourse($id)
    {
        $topics = \App\Models\Topic::where('course_id', $id)->get();
        return response()->json($topics);
    }
}
