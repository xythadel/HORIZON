<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of quizzes for a specific course and topic.
     */
    public function index(Request $request)
    {
        // Fetch quizzes filtered by course_id and topic_id
        $query = Quiz::with(['course', 'topic']);
        
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        return $query->get(); // Return filtered quizzes
    }

    /**
     * Store a newly created quiz.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'topic_id' => 'required|exists:topics,id',
            'is_published' => 'boolean',
        ]);

        // Create the quiz
        $quiz = Quiz::create($validated);

        return response()->json($quiz, 201); // Return the newly created quiz
    }

    /**
     * Update the specified quiz.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'exists:courses,id',
            'topic_id' => 'exists:topics,id',
            'is_published' => 'boolean',
        ]);

        // Update the quiz
        $quiz->update($validated);

        return response()->json($quiz); // Return the updated quiz
    }

    /**
     * Remove the specified quiz.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        
        return response()->json(['message' => 'Quiz deleted successfully']);
    }
}
