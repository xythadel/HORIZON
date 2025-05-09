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
        //retrun all courses
        return response()->json(Course::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //stores name and description of the course
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Course::create($validated);
        return redirect()->route('courses.index')->with('success', 'Course created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //shows the course with the given id
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //edit the course with the given id
        return view('courses.edit', compact('course'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //updates the course with the given id
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Course updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //deletes the course with the given id
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted!');
    }
}
