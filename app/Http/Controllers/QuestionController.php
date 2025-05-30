<?php
namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
            'points' => 'nullable|integer',
            'difficulty' => 'nullable|integer|min:1|max:5',
        ]);

        return Question::create($validated);
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'sometimes|string',
            'points' => 'nullable|integer',
            'difficulty' => 'nullable|integer|min:1|max:5',
        ]);

        $question->update($validated);
        return $question;
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->noContent();
    }
}
