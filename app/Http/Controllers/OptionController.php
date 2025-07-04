<?php
namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:quizzes,id',
            'options' => 'required|array|min:1',
            'options.*.option_text' => 'required|string',
        ]);

        foreach ($validated['options'] as $opt) {
            Option::create([
                'question_id' => $validated['question_id'],
                'option_text' => $opt['option_text'],
            ]);
        }

        return response()->json(['message' => 'Options stored'], 201);
    }

    public function update(Request $request, Option $option)
    {
        $validated = $request->validate([
            'option_text' => 'sometimes|string',

        ]);

        $option->update($validated);
        return $option;
    }

    public function getOptionsByQuestionId($questionId)
    {
        $options = Option::where('question_id', $questionId)->get(['option_text']);
        return response()->json($options);
    }

    public function destroy(Option $option)
    {
        $option->delete();
        return response()->noContent();
    }
}
