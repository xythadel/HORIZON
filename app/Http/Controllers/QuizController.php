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

        return $query->get();
    }

    /**
     * Store a newly created quiz.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questionCategory' => 'required|string|max:255',
            'quizStatus' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
            'is_published' => 'boolean',
            'answer' => 'required|string|max:255',
            'questionType' => 'required|string|max:255',
            'score' => 'required|integer',
            'course_id' => 'integer'
        ]);

        $quiz = Quiz::create($validated);

        return response()->json($quiz, 201);
    }

    public function fetchperCourse($id){
        $displayQuiz = Quiz::where('course_id','=',$id)->where('quizStatus','ACTIVE')->get();

        return response()->json($displayQuiz);
    }

    public function fetchperCoursearchives($id){
        $displayQuiz = Quiz::where('course_id','=',$id)->where('quizStatus','ARCHIVED')->get();

        return response()->json($displayQuiz);
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'exists:courses,id',
            'topic_id' => 'exists:topics,id',
            'is_published' => 'boolean',
            'score' => 'required|integer'
        ]);

        $quiz->update($validated);

        return response()->json($quiz);
    }

    public function displayQuiz(Request $request)
    {
        $quizzes = Quiz::where('questionCategory', 'Pre-test')
            ->with('topic', 'course')
            ->get()
            ->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'description' => $quiz->description,
                    'questionType' => $quiz->questionType,
                    'choices' => json_decode($quiz->choices, true),
                    'answer' => $quiz->answer,
                ];
            });

        return response()->json($quizzes);
    }

    public function getPretestByCourse($courseName)
    {
        $course = Course::where('name', $courseName)->first();

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $quizzes = Quiz::whereHas('topic', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->with('topic', 'topic.course')
            ->get()
            ->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'description' => $quiz->description,
                    'questionType' => $quiz->questionType,
                    'choices' => json_decode($quiz->choices, true),
                    'answer' => $quiz->answer,
                    'topic' => $quiz->topic->title ?? null,
                    'course' => $quiz->topic->course->course_name ?? null,
                    'score' => $quiz->score
                ];
            });

        return response()->json($quizzes);
    }


    /**
     * Remove the specified quiz.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->update([
            'quizStatus' => 'ARCHIVED'
        ]);

        return response()->json(['message' => 'Quiz archived successfully']);
    }
}
