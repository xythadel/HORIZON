<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Course::all();

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
    public function store(Request $request)
    {
        //stores name and description of the course
        $course = Course::create($request->only(['name', 'description']));
        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //shows the course with the given id
        return response()->json($course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //updates the course with the given id
        $course->update($request->only(['name', 'description']));
        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //deletes the course with the given id
        $course->delete();
        return response()->json(['message' => 'Course deleted']);
    }
    // app/Http/Controllers/CourseController.php

    public function getTopicsByCourse($id)
    {
    $course = Course::with('topics')->findOrFail($id);
    return response()->json($course->topics);
    }

} 
