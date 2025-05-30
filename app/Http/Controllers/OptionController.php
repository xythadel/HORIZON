<?php
namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_text' => 'required|string',
            'is_correct' => 'boolean',
        ]);

        return Option::create($validated);
    }

    public function update(Request $request, Option $option)
    {
        $validated = $request->validate([
            'option_text' => 'sometimes|string',
            'is_correct' => 'boolean',
        ]);

        $option->update($validated);
        return $option;
    }

    public function destroy(Option $option)
    {
        $option->delete();
        return response()->noContent();
    }
}
